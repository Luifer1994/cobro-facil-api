<?php

namespace App\Http\Modules\RolesAndPermissions\Requests;

use App\Http\Bases\BaseFormRequest;
use Illuminate\Support\Facades\Auth;

class AsingPermissionsToRoleRequest extends BaseFormRequest
{
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
            'role_id'          => 'required|exists:roles,id',
            'permissions'      => 'required|array',
            'permissions.*'    => 'required|exists:permissions,id'
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
            'role_id.required'          => 'El campo rol es obligatorio',
            'role_id.exists'            => 'El rol no existe',
            'permissions.required'      => 'El campo permisos es obligatorio',
            'permissions.array'         => 'El campo permisos debe ser un array',
            'permissions.*.required'    => 'El campo permisos es obligatorio',
            'permissions.*.exists'      => 'El permiso no existe',
        ];
    }
}
