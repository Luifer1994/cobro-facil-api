<?php

namespace App\Http\Modules\Clients\Requests;

use App\Http\Bases\BaseFormRequest;

class UpdateClientRequest extends BaseFormRequest
{
    /**
     * Reglas de validación para el request.
     */
    public function rules(): array
    {
        return [
            'document' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|min:10|max:20',
            'email' => 'nullable|string|email|max:100|unique:clients,email,' . $this->id . ',id',
            'address' => 'nullable|string|max:200',
            'document_type_id' => 'required|integer|exists:document_types,id',
        ];
    }

    /**
     * Atributos que se deben incluir en la respuesta.
     */
    public function attributes(): array
    {
        return [
            'document' => 'documento',
            'name' => 'nombre',
            'phone' => 'celular',
            'email' => 'correo',
            'address' => 'dirección',
            'document_type_id' => 'tipo de documento'
        ];
    }
}
