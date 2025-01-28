<?php

namespace App\Http\Modules\Tenants\Requests;

use App\Http\Bases\BaseFormRequest;

class UpdateTenantRequest  extends BaseFormRequest
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
        $allowedFields = array_keys($this->rules());
        $this->replace($this->only($allowedFields));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'document_number'   => 'required|unique:tenants,document_number,' . $this->id . ',id,document_type_id,' . $this->input('document_type_id'),
            'address'           => 'nullable|string|max:255',
            'cell_phone'        => 'required|string|max:10',
            'email'             => 'required|string|max:255|unique:tenants,email,' . $this->id,
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'primary_color'     => 'required|string|max:10',
            'secondary_color'   => 'required|string|max:10',
            'document_type_id'  => 'required|integer|exists:document_types,id',
            'city_id'           => 'required|integer|exists:cities,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required'             => 'El nombre es requerido.',
            'name.string'               => 'El nombre debe ser un texto.',
            'name.max'                  => 'El nombre no debe exceder los 255 caracteres.',
            'document_number.required'  => 'El número de documento es requerido.',
            'document_number.unique'    => 'El número de documento ya está en uso.',
            'address.string'            => 'La dirección debe ser un texto.',
            'address.max'               => 'La dirección no debe exceder los 255 caracteres.',
            'cell_phone.string'         => 'El celular debe ser un texto.',
            'cell_phone.string'         => 'El celular debe ser un texto.',
            'cell_phone.max'            => 'El celular no debe exceder los 20 caracteres.',
            'email.required'            => 'El correo electrónico es requerido.',
            'email.string'              => 'El correo electrónico debe ser un texto.',
            'email.max'                 => 'El correo electrónico no debe exceder los 255 caracteres.',
            'email.unique'              => 'El correo electrónico ya está en uso.',
            'logo.image'                => 'El logo debe ser una imagen.',
            'logo.mimes'                => 'El logo debe ser de tipo: jpeg, png, jpg, webp.',
            'logo.max'                  => 'El logo no debe exceder los 2048 kilobytes.',
            'primary_color.required'    => 'El color primario es requerido.',
            'primary_color.string'      => 'El color primario debe ser un texto.',
            'primary_color.max'         => 'El color primario no debe exceder los 10 caracteres.',
            'secondary_color.required'  => 'El color secundario es requerido.',
            'secondary_color.string'    => 'El color secundario debe ser un texto.',
            'secondary_color.max'       => 'El color secundario no debe exceder los 10 caracteres.',
            'document_type_id.required' => 'El tipo de documento es requerido.',
            'document_type_id.integer'  => 'El tipo de documento debe ser un número entero.',
            'document_type_id.exists'   => 'El tipo de documento no existe.',
            'city_id.required'          => 'La ciudad es requerida.',
            'city_id.integer'           => 'La ciudad debe ser un número entero.',
            'city_id.exists'            => 'La ciudad no existe.',
        ];
    }
}
