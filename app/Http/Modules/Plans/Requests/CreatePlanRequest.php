<?php

namespace App\Http\Modules\Plans\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreatePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name'              => 'required|string|max:255|unique:plans',
            'description'       => 'nullable|string|max:255',
            'price'             => 'required|numeric|min:0',
            'number_of_month'   => 'required|integer|min:1',
        ];

        if ($this->method() === 'PUT') {
            $rules['name'] = 'required|string|max:255|unique:plans,name,' . $this->route('id');
        }

        return $rules;
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
            'name.string'               => 'El nombre debe ser una cadena de texto.',
            'name.max'                  => 'El nombre no debe ser mayor a 255 caracteres.',
            'description.string'        => 'La descripción debe ser una cadena de texto.',
            'description.max'           => 'La descripción no debe ser mayor a 255 caracteres.',
            'price.required'            => 'El precio es requerido.',
            'price.numeric'             => 'El precio debe ser un número.',
            'price.min'                 => 'El precio no debe ser menor a 0.',
            'number_of_month.required'  => 'El número de meses es requerido.',
            'number_of_month.integer'   => 'El número de meses debe ser un número entero.',
            'number_of_month.min'       => 'El número de meses no debe ser menor a 1.',
            'name.unique'               => 'El nombre ya está en uso.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
