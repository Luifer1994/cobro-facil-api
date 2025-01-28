<?php

use App\Http\Modules\Plans\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Plan Routes
|--------------------------------------------------------------------------
*/

Route::prefix('plans')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::controller(PlanController::class)->group(function () {
            Route::get('list-actives', 'allActive')->middleware('permission:plans-list-actives');
            Route::get('list', 'index')->middleware('permission:plans-list');
            Route::post('create', 'store')->middleware('permission:plans-create');
            Route::get('show/{id}', 'show')->middleware('permission:plans-show');
            Route::put('update/{id}', 'update')->middleware('permission:plans-update');
            Route::post('change-status/{id}', 'changeStatus')->middleware('permission:plans-change-status');
        });
    });
});
