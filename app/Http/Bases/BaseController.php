<?php

namespace App\Http\Bases;

use App\Http\Controllers\Controller;
use App\Support\Result;
use Illuminate\Http\JsonResponse;
use App\Facades\Response;

class BaseController extends Controller
{
    /**
     * Devuelve una respuesta JSON basada en el resultado.
     *
     * @param Result $result
     * @return JsonResponse
     */
    protected function response(Result $result): JsonResponse
    {
        if ($result->isSuccess()) {
            return Response::apiJson(
                message: $result->getMessage(),
                data: $result->getValue(),
                statusCode: JsonResponse::HTTP_OK
            );
        } else {
            return Response::apiJson(
                message: $result->getError(),
                statusCode: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
