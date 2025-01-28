<?php

namespace App\Http\Modules\Installments\Requests;

use App\Http\Bases\BaseFormRequest;

class InstallmentRequest extends BaseFormRequest
{
    /**
     * Reglas de validación para el request.
     */
    public function rules(): array
    {
        return [
            // Define tus reglas de validación aquí
        ];
    }

    /**
     * Mensajes personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            // Define tus mensajes personalizados aquí
        ];
    }
}