<?php

namespace App\Http\Modules\Users\Requests;

use App\Http\Bases\BaseFormRequest;

class UpdateUserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'             => 'required|string:max:255',
            'email'            => 'required|email|unique:users,email,' . $this->id . ',id',
            'role_id'          => 'required|exists:roles,id',
            'password'         => 'nullable|string|min:8',
            'phone'            => 'nullable|string|min:10',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'name.required'             => 'El nombre es requerido',
            'email.required'            => 'El campo email es obligatorio',
            'email.email'               => 'El campo email debe ser un email',
            'email.unique'              => 'El email ya está en uso',
            'password.required'         => 'El campo contraseña es obligatorio',
            'password.string'           => 'El campo contraseña debe ser un string',
            'password.min'              => 'El campo contraseña debe tener mínimo 8 caracteres',
            'role_id.required'          => 'El campo rol es obligatorio',
            'role_id.exists'            => 'El rol no existe',
            'phone.required'            => 'El campo teléfono es obligatorio',
            'phone.string'           => 'El campo teléfono debe ser un string',
            'phone.min'              => 'El campo teléfono debe tener mínimo 10 caracteres',
        ];
    }
}
