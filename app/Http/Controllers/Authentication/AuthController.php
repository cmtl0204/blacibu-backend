<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\Auth\AuthChangePasswordRequest;
use App\Http\Requests\Authentication\Auth\AuthLoginRequest;
use App\Http\Requests\Authentication\Auth\AuthPasswordForgotRequest;
use App\Http\Requests\Authentication\Auth\AuthResetPasswordRequest;
use App\Http\Requests\Authentication\Auth\AuthUnlockRequest;
use App\Http\Requests\Authentication\Auth\AuthUserUnlockRequest;
use App\Http\Requests\Authentication\Auth\AuthGetRolesRequest;
use App\Http\Requests\Authentication\Auth\AuthGetPermissionsRequest;
use App\Http\Requests\Authentication\Auth\AuthResetAttemptsRequest;
use App\Http\Requests\Authentication\Auth\AuthLogoutAllRequest;
use App\Http\Requests\Authentication\Auth\AuthLogoutRequest;
use App\Http\Requests\Authentication\Auth\AuthGenerateTransactionalCodeRequest;
use App\Mail\Authentication\EmailVerifiedMailable;
use App\Mail\EmailMailable;
use App\Mail\Authentication\PasswordForgotMailable;
use App\Mail\Authentication\UserUnlockMailable;
use App\Models\App\Catalogue;
use App\Models\App\Location;
use App\Models\App\Professional;
use App\Models\Authentication\PasswordReset;
use App\Models\App\Status;
use App\Models\Authentication\Shortcut;
use App\Models\Authentication\System;
use App\Models\Authentication\TransactionalCode;
use App\Models\Authentication\UserUnlock;
use App\Models\Authentication\User;
use App\Models\Authentication\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class  AuthController extends Controller
{
    function register(Request $request)
    {
        if (User::where('username', $request->input('register.username'))->first()) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Número de documento ya existe: ' . $request->input('register.username'),
                    'detail' => 'Por favor inicie sesión o ingrese otro número de documento',
                    'code' => '400'
                ]
            ], 400);
        }

        if (User::where('email', $request->input('register.email'))->first()) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Correo electrónico ya existe: ' . $request->input('register.email'),
                    'detail' => 'Por favor inicie sesión o ingrese otro correo electrónico',
                    'code' => '400'
                ]
            ], 400);
        }

        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $statusUser = Status::firstWhere('code', $catalogues['status']['active']);
        $role = Role::where('code', $request->input('register.type'))->first();
        $lang = Catalogue::find($request->input('register.lang.id'));
        $identificationType = Catalogue::find($request->input('register.identification_type.id'));
        $country = Location::find($request->input('register.country.id'));
        $statusProfessional = Status::firstWhere('code', $catalogues['status']['in_revision']);

        $user = new User();
        $user->username = $request->input('register.username');
        $user->identification = $request->input('register.username');
        $user->name = $request->input('register.name');
        $user->lastname = $request->input('register.lastname');
        $user->email = $request->input('register.email');
        $user->password = $request->input('register.password');
        $user->is_changed_password = true;
        $user->lang()->associate($lang);
        $user->identificationType()->associate($identificationType);
        $user->status()->associate($statusUser);

        $professional = new Professional();
        $professional->country()->associate($country);
        $professional->status()->associate($statusProfessional);

//        DB::transaction(function () use ($user, $professional, $role) {
        $user->save();
        $user->roles()->attach($role);
        $professional->user()->associate($user);
        $professional->save();
        $this->createShortcuts($user, $role);
//        });
        $this->emailVerifiedDirect($user, $role->system()->first()->id);
        return response()->json([
            'data' => $professional,
            'msg' => [
                'summary' => 'Su cuenta ha sido creada',
                'detail' => 'Por favor verifique su correo electrónico para validar su cuenta',
                'code' => '201'
            ]
        ], 201);
    }

    function handleProviderCallback($driver)
    {
        $userSocialite = Socialite::driver($driver)->stateless()->user();
        $user = User::firstWhere('email', $userSocialite->getEmail());
        $system = System::find(1);
        if ($user) {
            if ($userSocialite->user['verified_email']) {
                $user->markEmailAsVerified();
            }
            $token = $user->createToken($userSocialite->getEmail())->accessToken;
//            $url = "http://siga.test:4200/#/auth/login?username={$user->username}&token={$token}";
            $url =  $system->redirect."auth/login?username={$user->username}&token={$token}";

            return redirect()->to($url);
        }

//        $url = "http://localhost:4200/#/auth/unregistered-user?email={$userSocialite->getEmail()}"
        $url = $system->redirect."auth/register?email={$userSocialite->getEmail()}"
            . "&given_name={$userSocialite->user['given_name']}" .
            "&family_name={$userSocialite->user['family_name']}";

        return redirect()->to($url);
    }

    function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    function registerSocialiteUser(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->identification = $request->username;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $token = $user->createToken($user->email)->accessToken;

        $detail = '';
        if (!$user->email_verified_at) {
            $detail = "Revise su correo para verificar su cuenta";
            Mail::to($user->email)
                ->send(new EmailVerifiedMailable(
                    'Verificación de Correo Electrónico',
                    json_encode(['user' => $user]),
                    null,
                    $request->input('system')
                ));
        }

        return response()->json([
            'data' => $token,
            'msg' => [
                'summary' => 'Usuario registrado correctamente',
                'detail' => $detail,
                'code' => '201',
            ]], 201);
    }

    function incorrectPassword($username)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);

        $user = User::firstWhere('username', $username);

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        $user->attempts = $user->attempts - 1;
        $user->save();

        if ($user->attempts <= 0) {
            $user->status()->associate(Status::firstWhere('code', $catalogues['status']['locked']));
            $user->attempts = 0;
            $user->save();

            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Oops! Su usuario ha sido bloqueado!',
                    'detail' => 'Demasiados intentos de inicio de sesión',
                    'code' => '429'
                ]], 429);
        }

        return response()->json([
            'data' => $user->attempts,
            'msg' => [
                'summary' => 'Contrasaña incorrecta',
                'detail' => "Oops! le quedan {$user->attempts} intentos",
                'code' => '401',
            ]], 401);
    }

    function resetAttempts(AuthResetAttemptsRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        $user->attempts = User::ATTEMPTS;
        $user->save();

        return response()->json([
            'data' => $user->attempts,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '201',
            ]], 201);
    }

    function logout(AuthLogoutRequest $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '201'
            ]], 201);
    }

    function logoutAll(AuthLogoutRequest $request)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', $request->user()->id)
            ->update([
                'revoked' => true
            ]);

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Se cerró sesión en todos sus dipositivos',
                'detail' => '',
                'code' => '201'
            ]], 201);
    }

    function changePassword(AuthChangePasswordRequest $request)
    {

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrando',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        if (!Hash::check(trim($request->input('password_old')), $user->password)) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'La contraseña actual no coincide con la contraseña enviada',
                    'detail' => 'Intente de nuevo',
                    'code' => '400'
                ]], 400);
        }

        $user->password = trim($request->input('password'));
        $user->is_changed_password = true;
        $user->save();

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Su contraseña fue actualizada',
                'detail' => '',
                'code' => '201'
            ]], 201);
    }

    function passwordForgot(AuthPasswordForgotRequest $request)
    {
        $user = User::where('username', $request->input('username'))
            ->orWhere('email', $request->input('username'))
            ->orWhere('personal_email', $request->input('username'))
            ->first();

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrando',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        $token = Str::random(70);
        PasswordReset::create([
            'username' => $user->username,
            'token' => $token
        ]);

        Mail::to($user->email)
            ->send(new PasswordForgotMailable(
                'Notificación de restablecimiento de contraseña',
                json_encode(['user' => $user, 'token' => $token]),
                null,
                $request->input('system')
            ));

        return response()->json([
            'data' => $token,
            'msg' => [
                'summary' => 'Correo enviado',
                'detail' => $this->hiddenStringEmail($user->email),
                'code' => '201'
            ]], 201);
    }

    function resetPassword(AuthResetPasswordRequest $request)
    {
        $passworReset = PasswordReset::where('token', $request->token)->first();

        if (!$passworReset) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Token no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '400'
                ]], 400);
        }

        if (!$passworReset->is_valid) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Token no valido',
                    'detail' => 'El token ya fue utilizado',
                    'code' => '403'
                ]], 403);
        }

        if ((new Carbon($passworReset->created_at))->addMinutes(10) <= Carbon::now()) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Token no valido',
                    'detail' => 'El token ha expirado',
                    'code' => '403'
                ]], 403);
        }

        $user = User::firstWhere('username', $passworReset->username);

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        $user->password = trim($request->password);
        $user->save();
        $passworReset->update(['is_valid' => false]);
        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Su contraseña fue restablecida',
                'detail' => 'Regrese al Login',
                'code' => '201'
            ]], 201);
    }

    function userLocked(AuthUserUnlockRequest $request)
    {
        $user = User::where('username', $request->input('username'))
            ->orWhere('email', $request->input('username'))
            ->orWhere('personal_email', $request->input('username'))
            ->first();

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrando',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }
        $token = Str::random(70);
        UserUnlock::create([
            'username' => $user->username,
            'token' => $token
        ]);

        Mail::to($user->email)
            ->send(new UserUnlockMailable(
                'Notificación de desbloqueo de usuario',
                json_encode(['user' => $user, 'token' => $token]),
                null,
                $request->input('system')
            ));

        return response()->json([
            'data' => $token,
            'msg' => [
                'summary' => 'Correo enviado',
                'detail' => $this->hiddenStringEmail($user->email),
                'code' => '201'
            ]], 201);
    }

    function unlockUser(AuthUnlockRequest $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $userUnlock = UserUnlock::where('username', $request->username)->where('token', $request->token)->first();
        if (!$userUnlock) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Token no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '400'
                ]], 400);
        }
        if (!$userUnlock->is_valid) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Token no valido',
                    'detail' => 'El token ya fue utilizado',
                    'code' => '403'
                ]], 403);
        }
        if ((new Carbon($userUnlock->created_at))->addMinutes(10) <= Carbon::now()) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Token no valido',
                    'detail' => 'El token ha expirado',
                    'code' => '403'
                ]], 403);
        }
        $user = User::firstWhere('username', $userUnlock->username);

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        $user->password = trim($request->password);
        $user->status()->associate(Status::firstWhere('code', $catalogues['status']['active']));
        $user->attempts = User::ATTEMPTS;

        $user->save();

        $userUnlock->update(['is_valid' => false]);
        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Su usuario fue desbloqueado',
                'detail' => 'Regrese al Login',
                'code' => '201'
            ]], 201);
    }

    private function emailVerifiedDirect($user, $systemId)
    {
        $user = User::where('username', $user->username)
            ->orWhere('email', $user->username)
            ->orWhere('personal_email', $user->username)
            ->first();

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrando',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        Mail::to($user->email)
            ->send(new EmailVerifiedMailable(
                'Verificación de Correo Electrónico',
                json_encode(['user' => $user]),
                null,
                $systemId
            ));

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Correo enviado',
                'detail' => $this->hiddenStringEmail($user->email),
                'code' => '201'
            ]], 201);
    }

    function emailVerified(Request $request)
    {
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->orWhere('personal_email', $request->username)
            ->first();

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrando',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        Mail::to($user->email)
            ->send(new EmailVerifiedMailable(
                'Verificación de Correo Electrónico',
                json_encode(['user' => $user]),
                null,
                $request->system
            ));

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Correo enviado',
                'detail' => $this->hiddenStringEmail($user->email),
                'code' => '201'
            ]], 201);
    }

    function verifyEmail(Request $request, User $user)
    {
        $system = System::findOrFail($request->system);
        $user->markEmailAsVerified();

        return view('pages.authentication.email-verified')
            ->with(['system' => $system, 'user' => $user]);;
    }

    function generateTransactionalCode(AuthGenerateTransactionalCodeRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrando',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }
        $token = mt_rand(100000, 999999);
        TransactionalCode::create([
            'username' => $user->username,
            'token' => $token
        ]);

        Mail::to($user->email)
            ->send(new EmailMailable(
                'Información Código de Seguridad',
                json_encode(['user' => $user])
            ));
        $domainEmail = strlen($user->email) - strpos($user->email, "@");

        return response()->json([
            'data' => $this->hiddenString($user->email, 3, $domainEmail),
            'msg' => [
                'summary' => 'Correo enviado',
                'detail' => $this->hiddenString($user->email, 3, $domainEmail),
                'code' => '201'
            ]], 201);
    }

    function verifyTransactionalCode(AuthUnlockRequest $request)
    {
        $transactionalCode = TransactionalCode::firstWhere('token', $request->token);

        if (!$transactionalCode) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Código no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '400'
                ]], 400);
        }
        if (!$transactionalCode->is_valid) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Código no valido',
                    'detail' => 'El código ya fue utilizado',
                    'code' => '403'
                ]], 403);
        }
        if ((new Carbon($transactionalCode->created_at))->addMinutes(2) <= Carbon::now()) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Código no válido',
                    'detail' => 'El código ha expirado',
                    'code' => '403'
                ]], 403);
        }
        $user = User::firstWhere('username', $transactionalCode->username);
        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        $transactionalCode->update(['is_valid' => false]);
        return response()->json([
            'data' => true,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '201'
            ]], 201);
    }

    private function hiddenStringEmail($email, $start = 2)
    {
        $end = strlen($email) - strpos($email, "@");
        $len = strlen($email);
        return substr($email, 0, $start) . str_repeat('*', $len - ($start + $end)) . substr($email, $len - $end, $end);
    }

    function getRoles(AuthGetRolesRequest $request)
    {
        $user = $request->user();

        $roles = $user->roles()
            ->where('system_id', $request->input('system'))
            ->get();

        if ($roles->count() === 0) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No tiene roles asignados en esta Institución',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        return response()->json([
            'data' => $roles,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }

    function getPermissions(AuthGetPermissionsRequest $request)
    {
        $role = Role::find($request->input('role'));

        if (!$role) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'El rol seleccionado no existe o le fue retirado',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        $permissions = $role->permissions()
            ->with(['route' => function ($route) {
                $route->whereHas('module')
                ->with('module')->with('type')->with('status');
            }])
            ->where('system_id', $request->system)
            ->get();

        if ($permissions->count() === 0) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No tiene permisos para el rol seleccionado',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]], 404);
        }

        return response()->json([
            'data' => $permissions,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }

    function createShortcuts($user, $role)
    {
        $permissions = $role->permissions()->with(['route' => function ($route) {
            $route->orderBy('order');
        }])->get();
        if ($role->code === 'CERTIFIED') {
            $j = 5;
        }

        if ($role->code === 'RECERTIFIED') {
            $j = 9;
        }

        $shortcut = new Shortcut();
        $shortcut->name = $permissions[0]->route['name'];
        $shortcut->image = "routes/route4.png";
        $shortcut->user()->associate($user);
        $shortcut->role()->associate($role);
        $shortcut->permission()->associate($permissions[0]);
        $shortcut->save();

        for ($i = 1; $i < $permissions->count(); $i++) {
            $shortcut = new Shortcut();
            $shortcut->name = $permissions[$i]->route['name'];
            $shortcut->image = "routes/route$j.png";
            $shortcut->user()->associate($user);
            $shortcut->role()->associate($role);
            $shortcut->permission()->associate($permissions[$i]);
            $shortcut->save();
            $j++;
        }
    }
}
