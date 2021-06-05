<?php

namespace App\Http\Controllers\Authentication;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\User\UserCreateRequest;
use App\Http\Requests\Authentication\UserAdministration\UserAdminIndexRequest;
use App\Http\Requests\Authentication\UserRequest;
use App\Models\App\Professional;
use App\Models\Authentication\PassworReset;
use App\Models\Authentication\Role;
use App\Models\App\Catalogue;
use App\Models\App\Status;
use App\Models\Authentication\User;
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
                    $query->firstlastname($search);
                    $query->firstname($search);
                    $query->identification($search);
                    $query->secondlastname($search);
                    $query->secondname($search);
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
            ->with(['roles' => function ($roles) use ($request) {
                $roles
                    ->with(['permissions' => function ($permissions) {
                        $permissions->with(['route' => function ($route) {
                            $route->with('module')->with('type')->with('status');
                        }])->with('institution');
                    }]);
            }])->with(['professional' => function ($professional) {
                $professional->with('status');
            }])
            ->paginate($request->input('per_page'));
        if ($request->has('search')) {
            $users = $users->where(function ($query) use ($search) {
                $query->email($search);
                $query->firstlastname($search);
                $query->name($search);
                $query->identification($search);
                $query->secondlastname($search);
            });
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

    public function getProfessionalDocuments(Request $request)
    {
        $professional = Professional::find($request->input('professional_id'));

        $documents = Catalogue::where('type', 'DOCUMENT')
            ->with(['conferences' => function ($conferences) use ($professional, $request) {
                $conferences->with(['file'])
                    ->where('professional_id', $professional->id);
            }])
            ->with(['documents' => function ($conferences) use ($professional, $request) {
                $conferences->with(['file'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();
        return response()->json([
            'data' => $documents,
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
        $user->first_name = $request->input('user.first_name');
        $user->second_name = $request->input('user.second_name');
        $user->first_lastname = $request->input('user.first_lastname');
        $user->second_lastname = $request->input('user.second_lastname');
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
            $user->first_name = $request->input('user.first_name');
            $user->first_lastname = $request->input('user.first_lastname');
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
