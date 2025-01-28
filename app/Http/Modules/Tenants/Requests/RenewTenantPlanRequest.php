<?php

namespace App\Http\Modules\Tenants\Requests;

use App\Http\Bases\BaseFormRequest;

class RenewTenantPlanRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'id' => strtolower($this->id),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'tenant_id'      => 'required|string|max:20|exists:tenants,id',
            'plan_id' => 'required|integer|exists:plans,id,is_active,1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        //español
        return [
            'tenant_id.required' => 'El id del inquilino es requerido',
            'tenant_id.string' => 'El id del inquilino debe ser un texto',
            'tenant_id.max' => 'El id del inquilino debe tener como máximo 20 caracteres',
            'tenant_id.exists' => 'El id del inquilino no existe',
            'plan_id.required' => 'El id del plan es requerido',
            'plan_id.integer' => 'El id del plan debe ser un número entero',
            'plan_id.exists' => 'El id del plan no existe',
        ];
    }

}
