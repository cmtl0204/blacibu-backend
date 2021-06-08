<?php

namespace App\Http\Controllers\Authentication;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\User\UserCreateRequest;
use App\Http\Requests\Authentication\UserAdministration\UserAdminIndexRequest;
use App\Http\Requests\Authentication\UserRequest;
use App\Models\App\Certificate;
use App\Models\App\Conference;
use App\Models\App\Document;
use App\Models\App\Location;
use App\Models\App\Payment;
use App\Models\App\Professional;
use App\Models\Authentication\PassworReset;
use App\Models\Authentication\Role;
use App\Models\App\Catalogue;
use App\Models\App\Status;
use App\Models\Authentication\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class  UserAdministrationController extends Controller
{
    public function index(Request $request)
    {
        $system = $request->input('system');
        $search = $request->input('search');

        if ($request->has('search')) {
            $users = User::whereHas('roles', function ($role) use ($system) {
                $role->where('system_id', '=', $system);
            })
                ->where(function ($query) use ($search) {
                    $query->email($search);
                    $query->lastname($search);
                    $query->identification($search);
                    $query->name($search);
                })
                ->with(['institutions' => function ($institutions) {
                    $institutions->orderBy('name');
                }])
                ->with(['roles' => function ($roles) use ($request) {
                    $roles
                        ->with(['permissions' => function ($permissions) {
                            $permissions->with(['route' => function ($route) {
                                $route->with('module')->with('type')->with('status');
                            }])->with('institution');
                        }]);
                }])
                ->paginate($request->input('per_page'));
        } else {
            $users = User::whereHas('roles', function ($role) use ($system) {
                $role->where('system_id', '=', $system);
            })
                ->with(['institutions' => function ($institutions) {
                    $institutions->orderBy('name');
                }])
                ->with(['roles' => function ($roles) use ($request) {
                    $roles
                        ->with(['permissions' => function ($permissions) {
                            $permissions->with(['route' => function ($route) {
                                $route->with('module')->with('type')->with('status');
                            }])->with('institution');
                        }]);
                }])
                ->paginate($request->input('per_page'));
        }

        if ($users->count() === 0) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No se encontraron usuarios',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]
            ], 404);
        }
        return response()->json($users, 200);
    }

    public function getProfessionals(Request $request)
    {
        $system = $request->input('system');
        $search = $request->input('search');
        $selectedRole = strtoupper($request->input('professional_role'));

        $users = User::whereHas('roles', function ($role) use ($system, $selectedRole) {
            $role->where('code', '=', $selectedRole)->where('system_id', '=', $system);
        })
            ->with(['professional' => function ($professional) {
                $professional->with('status');
            }]);

        if ($request->has('search')) {
            $users = $users->where(function ($query) use ($search) {
                $query->email($search);
                $query->lastname($search);
                $query->name($search);
                $query->identification($search);
            });
        }
        $users = $users->orderBy('lastname')->paginate($request->input('per_page'));
        if ($users->count() === 0) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No se encontraron usuarios',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]
            ], 404);
        }
        return response()->json($users, 200);
    }

    public function getProfessional($id)
    {
        $professional = Professional::where('id', $id)->with(['user' => function ($user) {
            $user->with(['identificationType'])->with(['address' => function ($address) {
                $address->with('location');
            }]);
        }])->with(['status', 'socialmedia', 'country'])->with(['payment' => function ($payment) {
            $payment->with(['file', 'status']);
        }])->first();

        return response()->json([
            'data' => $professional,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }

    public function reviseProfessional(Request $request)
    {
        $status = Status::where('code', 'IN_REVISION')->first();
        $professional = Professional::find($request->input('professional_id'));
        $professional->status()->associate($status);
        $professional->save();
        return response()->json([
            'data' => $professional,
            'msg' => [
                'summary' => 'EN REVISIÓN',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    public function approveProfessional(Request $request)
    {
        $status = Status::where('code', 'ACCEPTED')->first();
        $professional = Professional::find($request->input('professional_id'));
        $professional->status()->associate($status);
        $professional->save();
        return response()->json([
            'data' => $professional,
            'msg' => [
                'summary' => 'APROBADO',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    public function approveDocumentProfessional(Request $request)
    {
        $status = Status::where('code', 'ACCEPTED')->first();
        switch ($request->input('type')) {
            case 'CONSTANCY':
            case 'DOCUMENT':
                $model = Document::find($request->input('id'));
                break;
            case 'RECONFERENCE':
            case 'CONFERENCE':
                $model = Conference::find($request->input('id'));
                break;
            case 'RECERTIFICATE':
            case 'CERTIFICATE':
                $model = Certificate::find($request->input('id'));
                break;
            case 'PAYMENT':
                $model = Payment::find($request->input('id'));
                break;
        }

        $model->status()->associate($status);
        $model->save();

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'APROBADO',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    public function rejectProfessional(Request $request)
    {
        $status = Status::where('code', 'REJECTED')->first();
        $professional = Professional::find($request->input('professional_id'));
        $professional->status()->associate($status);
        $professional->save();
        return response()->json([
            'data' => $professional,
            'msg' => [
                'summary' => 'RECHAZADO',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    public function rejectDocumentProfessional(Request $request)
    {
        $status = Status::where('code', 'REJECTED')->first();
        switch ($request->input('type')) {
            case 'CONSTANCY':
            case 'DOCUMENT':
                $model = Document::find($request->input('id'));
                break;
            case 'RECONFERENCE':
            case 'CONFERENCE':
                $model = Conference::find($request->input('id'));
                break;
            case 'RECERTIFICATE':
            case 'CERTIFICATE':
                $model = Certificate::find($request->input('id'));
                break;
            case 'PAYMENT':
                $model = Payment::find($request->input('id'));
                break;
        }
        $model->status()->associate($status);
        $model->observations = $request->input('observations');
        $model->save();

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'RECHAZADO',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    public function getCertifiedDocuments(Request $request)
    {
        $professional = Professional::find($request->input('professional_id'));
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);

        $documents = Catalogue::where('type', $catalogues['catalogue']['document']['type'])
            ->with(['document' => function ($document) use ($professional, $request) {
                $document->with(['file', 'status'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();

        $conferences = Catalogue::where('type', $catalogues['catalogue']['conference']['type'])
            ->with(['conferences' => function ($conferences) use ($professional, $request) {
                $conferences->with(['file', 'status'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();

        $certificates = Catalogue::where('type', $catalogues['catalogue']['certificate']['type'])
            ->with(['certificates' => function ($certificates) use ($professional, $request) {
                $certificates->with(['file', 'status'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();
        return response()->json([
            'data' => [
                'documents' => $documents,
                'conferences' => $conferences,
                'certificates' => $certificates,
            ],
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function getReCertifiedDocuments(Request $request)
    {
        $professional = Professional::find($request->input('professional_id'));
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);

        $documents = Catalogue::where('type', $catalogues['catalogue']['constancy']['type'])
            ->with(['document' => function ($document) use ($professional, $request) {
                $document->with(['file', 'status'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();

        $conferences = Catalogue::where('type', $catalogues['catalogue']['reconference']['type'])
            ->with(['conferences' => function ($conferences) use ($professional, $request) {
                $conferences->with(['file', 'status'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();

        $certificates = Catalogue::where('type', $catalogues['catalogue']['recertificate']['type'])
            ->with(['certificates' => function ($certificates) use ($professional, $request) {
                $certificates->with(['file', 'status'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();
        return response()->json([
            'data' => [
                'documents' => $documents,
                'conferences' => $conferences,
                'certificates' => $certificates,
            ],
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function show($userId, Request $request)
    {
        $system = $request->input('system');
        $user = User::whereHas('roles', function ($role) use ($system) {
            $role->where('system_id', '=', $system);
        })
            ->with(['institutions' => function ($institutions) {
                $institutions->orderBy('name');
            }])
            ->with(['roles' => function ($roles) use ($request) {
                $roles->with(['permissions' => function ($permissions) {
                    $permissions->with(['route' => function ($route) {
                        $route->with('module')->with('type')->with('status');
                    }])->with('institution');
                }]);
            }])
            ->where('id', $userId)
            ->first();
        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No se encontraró al usuario',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]
            ], 404);
        }
        return response()->json([
            'data' => $user,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->identification = $request->input('user.identification');
        $user->username = $request->input('user.username');
        $user->name = $request->input('user.name');
        $user->lastname = $request->input('user.lastname');
        $user->birthdate = $request->input('user.birthdate');
        $user->email = $request->input('user.email');
        $user->password = Hash::make($request->input('user.password'));

        $user->status()->associate(Status::getInstance($request->input('user.status')));
        $user->save();

        return response()->json([
            'data' => $user,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '201'
            ]
        ], 201);
    }

    public function update(Request $request, $userId)
    {
        $system = $request->input('system');
        $user = User::whereHas('roles', function ($role) use ($system) {
            $role->where('system_id', '=', $system);
        })->where('id', $userId)
            ->get();

        if ($user->count() == 0) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no encontrado',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]
            ], 404);
        } else {
            $user = User::find($userId);
            $user->identification = $request->input('user.identification');
            $user->username = $request->input('user.username');
            $user->name = $request->input('user.name');
            $user->lastname = $request->input('user.lastname');
            $user->birthdate = $request->input('user.birthdate');
            $user->email = $request->input('user.email');
            $user->phone = $request->input('user.phone');

            $user->save();
            return response()->json([
                'data' => $user,
                'msg' => [
                    'summary' => 'update',
                    'detail' => '',
                    'code' => '201'
                ]
            ], 201);
        }
    }

    public function delete(Request $request)
    {
        User::destroy($request->input('ids'));

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Usuario(s) eliminado(s)',
                'detail' => 'Se eliminó correctamente',
                'code' => '201'
            ]], 201);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    private function filter($conditions)
    {
        $filters = array();
        foreach ($conditions as $condition) {
            if ($condition['match_mode'] === 'contains') {
                array_push($filters, array($condition['field'], $condition['logic_operator'], '%' . $condition['value'] . '%'));
            }
            if ($condition['match_mode'] === 'start') {
                array_push($filters, array($condition['field'], $condition['logic_operator'], $condition['value'] . '%'));
            }
            if ($condition['match_mode'] === 'end') {
                array_push($filters, array($condition['field'], $condition['logic_operator'], '%' . $condition['value']));
            }
        }
        return $filters;
    }
}
