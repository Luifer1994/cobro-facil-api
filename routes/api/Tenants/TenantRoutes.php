<?php

use App\Http\Modules\Tenants\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenants Routes
|--------------------------------------------------------------------------
*/

Route::prefix('tenants')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::controller(TenantController::class)->group(function () {
            Route::get('list', 'index')->middleware('permission:tenants-list');
            Route::post('create', 'store')->middleware('permission:tenants-create');
            Route::get('show/{id}', 'show')->middleware('permission:tenants-show');
            Route::post('update/{id}', 'update')->middleware('permission:tenants-update');
            Route::post('renovate-plan', 'renovatePlan')->middleware('permission:tenants-renovate-plan');
            Route::post('change-status/{id}', 'changeStatus')->middleware('permission:tenants-change-status');
        });
    });

    Route::get('get-tenant/{id}', [TenantController::class, 'validateActiveTenant']);
});
