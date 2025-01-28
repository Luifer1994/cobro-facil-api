<?php

use App\Http\Modules\Cities\Controllers\CityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cities Routes
|--------------------------------------------------------------------------
*/

Route::prefix('cities')->group(function () {
  /* Route::group(['middleware' => 'auth:api'], function () { */
    Route::controller(CityController::class)->group(function () {
      Route::get('list-by-department/{departmentId}', 'listByDepartment');
      Route::get('list', 'index')/* ->middleware('permission:cities-list') */;
      Route::get('all', 'all')/* ->middleware('permission:cities-list') */;
   /*  }); */
  });
});
