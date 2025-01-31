<?php

use App\Http\Modules\Loans\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Loans Routes
|--------------------------------------------------------------------------
*/

Route::prefix('loans')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::controller(LoanController::class)->group(function () {
            Route::get('list', 'index')->middleware('permission:loans-list');
            Route::get('show/{id}', 'show')->middleware('permission:loans-show');
            Route::post('create', 'store')->middleware('permission:loans-create');
            Route::put('update/{id}', 'update')->middleware('permission:loans-update');
        });
    });
});
