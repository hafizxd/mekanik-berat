<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\ItemController as ApiItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [ApiAuthController::class, 'login'])->name('login');
Route::post('register', [ApiAuthController::class, 'register'])->name('register');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [ApiAuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [ApiAuthController::class, 'refresh'])->name('refresh');

    Route::prefix('items')->name('items.')->controller(ApiItemController::class)->group(function () {
        Route::post('store', 'store')->name('store');
        Route::post('scan', 'scan')->name('scan');

        Route::get('/', 'list')->name('list');
        Route::get('{itemId}', 'show')->name('show');
        Route::delete('{itemId}/delete', 'delete')->name('delete');
        Route::get('{itemId}/reparations', 'listReparation');
        Route::post('{itemId}/reparations/store', 'storeReparation');
        Route::get('{itemId}/reparations/{reparationId}', 'showReparation');
        Route::post('{itemId}/reparations/{reparationId}/update', 'updateReparation');
    });
});
