<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Macro para unificar las respuestas JSON de la API.
         *
         * @param  mixed  $data       Información a devolver en la respuesta.
         * @param  string $message    Mensaje de la operación realizada.
         * @param  int    $statusCode Código de estado HTTP (por defecto 200).
         * 
         * Uso: return response()->apiJson($data, $message, $statusCode);
         */
        Response::macro('apiJson', function (
            string $message = '',
            $data = null,
            int $statusCode = 200
        ) {
            $success = $statusCode >= 200 && $statusCode < 300;

            if (! $success && $statusCode !== 422) {
                $data = null;
            }

            return response()->json([
                'success' => $success,
                'message' => $message,
                'data'    => $data,
            ], $statusCode);
        });
    }
}
