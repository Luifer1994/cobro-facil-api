<?php

namespace App\Http\Modules\RolesAndPermissions\Requests;

use App\Http\Bases\BaseFormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRoleRequest extends BaseFormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => strtolower(preg_replace('/[^A-Za-z0-9]/', '', $this->name)),
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'          => 'required|string|unique:roles,name',
            'description'   => 'required|string|max:50',
            'guard_name'    => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'name.required'         => 'El campo código es obligatorio',
            'name.string'           => 'El campo código debe ser un string',
            'name.unique'           => 'El código ya existe',
            'description.required'  => 'El campo nombre es obligatorio',
            'description.string'    => 'El campo nombre debe ser un string',
            'description.max'       => 'El campo nombre debe tener máximo 50 caracteres',
            'guard_name.required'   => 'El campo guard name es obligatorio',
            'guard_name.string'     => 'El campo guard name debe ser un string',
        ];
    }
}
