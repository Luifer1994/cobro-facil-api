<?php

namespace App\Http\Modules\Tenants\Requests;

use App\Http\Bases\BaseFormRequest;

class CreateTenantRequest extends BaseFormRequest
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
            'email' => strtolower($this->email),
            'id' => strtolower(preg_replace('/[^A-Za-z0-9]/', '', $this->id)),
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
            'id'            => 'required|string|max:20|unique:tenants,id',
            'name'              => 'required|string|max:255',
            'document_number'   => 'required|unique:tenants,document_number,NULL,id,document_type_id,' . $this->input('document_type_id'),
            'address'           => 'nullable|string|max:255',
            'cell_phone'        => 'required|string|max:10',
            'email'             => 'required|string|max:255|unique:tenants,email',
            'logo'              => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'primary_color'     => 'required|string|max:10',
            'secondary_color'   => 'required|string|max:10',
            'document_type_id'  => 'required|integer|exists:document_types,id',
            'city_id'           => 'required|integer|exists:cities,id',
            'plan_id'           => 'required|integer|exists:plans,id,is_active,1',
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
            'id'            => 'dominio',
            'name'              => 'nombre',
            'document_number'   => 'número de documento',
            'address'           => 'dirección',
            'cell_phone'        => 'celular',
            'email'             => 'correo electrónico',
            'logo'              => 'logo',
            'primary_color'     => 'color primario',
            'secondary_color'   => 'color secundario',
            'document_type_id'  => 'tipo de documento',
            'city_id'           => 'ciudad',
            'plan_id'           => 'plan',
        ];
    }
}
