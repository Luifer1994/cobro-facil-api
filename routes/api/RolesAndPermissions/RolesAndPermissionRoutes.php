<?php

use App\Http\Modules\RolesAndPermissions\Controllers\PermissionController;
use App\Http\Modules\RolesAndPermissions\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Roles and Permissions
|--------------------------------------------------------------------------
*/

Route::prefix('roles')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('paginate', 'index')->middleware('permission:list-roles');
            Route::get('list', 'listAll')->middleware('permission:list-all-roles');
            Route::get('show/{id}', 'show')->middleware('permission:show-roles');
            Route::post('assign-permissions', 'assignPermissionsToRole')->middleware('permission:assign-permissions-to-roles');
            Route::post('create', 'store')->middleware('permission:create-roles');
            Route::put('update/{id}', 'update')->middleware('permission:update-roles');
        });
    });
});
/* Route::prefix('permissions')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::controller(PermissionController::class)->group(function () {
            Route::get('list-by-rol/{rolId}', 'getPermissionsByRol')->middleware('permission:list-permissions-by-rol');
        });
    });
}); */
