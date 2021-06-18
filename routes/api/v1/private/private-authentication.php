<?php

use App\Http\Controllers\Authentication\ModuleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\UserController;
use App\Http\Controllers\Authentication\RoleController;
use App\Http\Controllers\Authentication\PermissionController;
use App\Http\Controllers\Authentication\RouteController;
use App\Http\Controllers\Authentication\ShortcutController;
use App\Http\Controllers\Authentication\SystemController;
use App\Http\Controllers\Authentication\UserAdministrationController;

//$middlewares = ['auth:api',  'check-role', 'check-status', 'check-attempts', 'check-permissions'];
//$middlewares = ['auth:api', 'verified', 'check-role',  'check-status', 'check-attempts', 'check-permissions'];

Route::get('test', function (\Illuminate\Http\Request $request) {
//                $request->user()->sendEmailVerificationNotification();
    return "hola mundo";
});
// ApiResources
Route::apiResource('user-admins', UserAdministrationController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('routes', RouteController::class);
Route::apiResource('shortcuts', ShortcutController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('systems', SystemController::class)->except('show');

// Auth
Route::prefix('auth')->group(function () {
    Route::get('roles', [AuthController::class, 'getRoles'])
        ->withoutMiddleware(['check-role', 'check-permissions']);
    Route::get('permissions', [AuthController::class, 'getPermissions'])
        ->withoutMiddleware(['check-permissions']);
    Route::put('change-password', [AuthController::class, 'changePassword'])
        ->withoutMiddleware(['check-role', 'check-permissions']);
    Route::post('transactional-code', [AuthController::class, 'generateTransactionalCode']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('logout-all', [AuthController::class, 'logoutAll']);
    Route::get('reset-attempts', [AuthController::class, 'resetAttempts'])
        ->withoutMiddleware(['check-role', 'check-permissions']);
    Route::post('test', function (\Illuminate\Http\Request $request) {
        return $request->user()->markEmailAsVerified();

    })->withoutMiddleware('verified');
});

// User
Route::prefix('user')->group(function () {
    Route::get('{username}', [UserController::class, 'show'])
        ->withoutMiddleware(['check-role', 'check-permissions']);
    Route::post('filters', [UserController::class, 'index']);
    Route::post('avatars', [UserController::class, 'uploadAvatar']);
    Route::get('export', [UserController::class, 'export']);
});

//User Administration
Route::prefix('user-admin')->group(function () {
    Route::get('professional/{professional}', [UserAdministrationController::class, 'getProfessional']);
    Route::get('professionals', [UserAdministrationController::class, 'getProfessionals']);
    Route::get('professionals/certified-documents', [UserAdministrationController::class, 'getCertifiedDocuments']);
    Route::get('professionals/re-certified-documents', [UserAdministrationController::class, 'getReCertifiedDocuments']);
    Route::put('professionals/approve', [UserAdministrationController::class, 'approveProfessional']);
    Route::put('professionals/documents/approve', [UserAdministrationController::class, 'approveDocumentProfessional']);
    Route::put('professionals/reject', [UserAdministrationController::class, 'rejectProfessional']);
    Route::put('professionals/documents/reject', [UserAdministrationController::class, 'rejectDocumentProfessional']);
    Route::put('professionals/revise', [UserAdministrationController::class, 'reviseProfessional']);
    Route::put('delete', [UserAdministrationController::class, 'delete']);
    Route::put('inactive/{user}', [UserAdministrationController::class, 'inactive']);
});

// Role
Route::prefix('role')->group(function () {
    Route::post('users', [RoleController::class, 'getUsers']);
    Route::post('permissions', [RoleController::class, 'getPermissions']);
    Route::post('assign-role', [RoleController::class, 'assignRole']);
    Route::post('remove-role', [RoleController::class, 'removeRole']);
});

// Module
Route::prefix('module')->group(function () {
    Route::get('menus', [ModuleController::class, 'getMenus']);
});
