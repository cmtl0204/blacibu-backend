<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\CatalogueController;
use App\Http\Controllers\App\ImageController;
use App\Http\Controllers\App\ProfessionalController;
use App\Http\Controllers\App\DocumentController;
use App\Http\Controllers\App\CertificateController;
use App\Http\Controllers\App\ConferenceController;
use App\Http\Controllers\App\FileController;
use App\Http\Controllers\App\LocationController;
use App\Http\Controllers\App\EmailController;

Route::apiResource('catalogues', CatalogueController::class);
Route::apiResource('locations', LocationController::class);
Route::get('countries', [LocationController::class, 'getCountries']);

Route::group(['prefix' => 'location'], function () {
    Route::get('get', [LocationController::class, 'getLocations']);
});

Route::group(['prefix' => 'image'], function () {
    Route::get('download', [ImageController::class, 'download']);
});

Route::group(['prefix' => 'file'], function () {
    Route::get('', [FileController::class, 'index']);
    Route::get('download', [FileController::class, 'download']);
    Route::put('delete', [FileController::class, 'delete']);
    Route::put('update/{file}', [FileController::class, 'update']);
    Route::delete('force-delete', [FileController::class, 'forceDelete']);
});

Route::group(['prefix' => 'professionals'], function () {
    Route::get('get', [ProfessionalController::class, 'getProfessional']);
    Route::get('payments', [ProfessionalController::class, 'getPayments']);
    Route::post('payments/file', [ProfessionalController::class, 'uploadPaymentsFiles']);
    Route::put('payments/update', [ProfessionalController::class, 'updatePayment']);
});

Route::group(['prefix' => 'documents'], function () {
    Route::get('', [DocumentController::class, 'index']);
    Route::put('delete', [DocumentController::class, 'delete']);
    Route::post('file', [DocumentController::class, 'uploadFiles']);
});

Route::group(['prefix' => 'certificates'], function () {
    Route::get('', [CertificateController::class, 'index']);
    Route::put('delete', [CertificateController::class, 'delete']);
    Route::put('update', [CertificateController::class, 'update']);
    Route::post('file', [CertificateController::class, 'uploadFiles']);
});

Route::group(['prefix' => 'conferences'], function () {
    Route::get('', [ConferenceController::class, 'index']);
    Route::put('delete', [ConferenceController::class, 'delete']);
    Route::put('update', [ConferenceController::class, 'update']);
    Route::post('file', [ConferenceController::class, 'uploadFiles']);
});

Route::group(['prefix' => 'emails'], function () {
    Route::post('send', [EmailController::class, 'send']);
});
