<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/


Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class
])->prefix('api/tenant')->group(function () {
    require base_path('routes/api/Auth/AuthRoutes.php');
    require base_path('routes/api/Users/UserRoutes.php');
    require base_path('routes/api/Cities/CityRoutes.php');
    require base_path('routes/api/DocumentTypes/DocumentTypesRoutes.php');
    require base_path('routes/api/RolesAndPermissions/RolesAndPermissionRoutes.php');
    require base_path('routes/api/Clients/ClientRoutes.php');
    require base_path('routes/api/Loans/LoansRoutes.php');
   /*  require base_path('routes/api/Installments/InstallmentsRoutes.php');
    require base_path('routes/api/Payments/PaymentsRoutes.php'); */
});
