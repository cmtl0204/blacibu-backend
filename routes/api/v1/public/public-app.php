<?php

use App\Http\Controllers\App\CatalogueController;
use App\Http\Controllers\App\LocationController;
use App\Http\Requests\Authentication\Auth\CreateClientRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::apiResource('catalogues', CatalogueController::class);

Route::apiResource('locations', LocationController::class);

Route::group(['prefix' => 'location'], function () {
    Route::get('get', [LocationController::class, 'getLocations']);
    Route::get('countries', [LocationController::class, 'getCountries']);
});

Route::get('init', function (CreateClientRequest $request) {

    if (env('APP_ENV') != 'local') {
        return 'El sistema se encuentra en producción.';
    }

    DB::select('drop schema if exists public cascade;');
    DB::select('drop schema if exists authentication cascade;');
    DB::select('drop schema if exists app cascade;');

    DB::select('create schema authentication;');
    DB::select('create schema app;');

    Artisan::call('migrate', ['--seed' => true]);

    Artisan::call('passport:client', [
        '--password' => true,
        '--name' => 'Password-' . $request->input('client_name'),
        '--quiet' => true,
    ]);

    Artisan::call('passport:client', [
        '--personal' => true,
        '--name' => 'Client-' . $request->input('client_name'),
        '--quiet' => true,
    ]);

    $clientSecret = DB::select("select secret from oauth_clients where name='" . 'Password-' . $request->input('client_name') . "'");

    return response()->json([
        'msg' => [
            'Los esquemas fueron creados correctamente.',
            'Las migraciones fueron creadas correctamente',
            'Cliente para la aplicación creado correctamente',
        ],
        'client' => $clientSecret[0]->secret
    ]);
});
