<?php

namespace App\Exceptions;

use App\Facades\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Stancl\Tenancy\Contracts\TenantCouldNotBeIdentifiedException as ContractsTenantCouldNotBeIdentifiedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->renderable(function (Throwable $e, $request) {
            return $this->handleException($request, $e);
        });
    }

    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return Response::apiJson(
                message: 'Error de autorización, no tiene permisos',
                statusCode: 403
            );
        } elseif ($exception instanceof RouteNotFoundException) {
            return Response::apiJson(
                message: 'Error de autenticación',
                statusCode: 401
            );
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $routerNotFound = $request->path();
            return Response::apiJson(
                message: "No se pudo encontrar la ruta" . ": {$routerNotFound}",
                statusCode: 404
            );
        } elseif ($exception instanceof AuthorizationException) {
            return Response::apiJson(
                message: 'Error de autorización, no tiene permisos',
                statusCode: 403
            );
        } elseif ($exception instanceof ContractsTenantCouldNotBeIdentifiedException) {
            return Response::apiJson(
                message: 'No se pudo identificar el tenant para el dominio proporcionado.',
                statusCode: 400
            );
        } else {
            custom_log($exception, 'Error en el sistema');
            return Response::apiJson(
                message: 'Error en el sistema',
                statusCode: 500
            );
        }
    }
}
