<?php

namespace App\Http\Bases;

use App\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            Response::apiJson(
                message: 'Error de validación',
                data: $validator->errors(),
                statusCode: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
