<?php

namespace App\Http\Modules\Auth\Requests;

use App\Http\Bases\BaseFormRequest;

class LoginRequest extends BaseFormRequest
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
        return  [
            'password' => 'required',
            'email' => 'required|email|exists:users,email'
        ];
    }

    /**
     * Name custom to attributes.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'email' => 'correo electrónico',
            'password' => 'contaseña',
        ];
    }
}
