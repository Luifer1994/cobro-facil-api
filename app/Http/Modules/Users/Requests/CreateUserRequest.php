<?php

namespace App\Http\Modules\Users\Requests;

use App\Http\Bases\BaseFormRequest;

class CreateUserRequest extends BaseFormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role_id'     => 'required|exists:roles,id',
            'phone'    => 'required|string|min:10',
        ];
    }

    /**
     * Obtener los nombres personalizados de los atributos.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'name'     => 'nombre',
            'email'    => 'correo electrónico',
            'password' => 'contraseña',
            'role_id'     => 'rol',
            'phone'    => 'teléfono',
        ];
    }
}
