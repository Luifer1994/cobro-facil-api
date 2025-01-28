<?php

namespace App\Http\Modules\Cities\Requests;

use App\Http\Bases\BaseFormRequest;
use App\Rules\ExistsInCentral;

class ListCitiesByDepartmentRequest extends BaseFormRequest
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
            'departmentId' => 'required|integer|exists:departments:id'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'departmentId' => $this->route('departmentId'),
        ]);
    }

    /**
     * Name custom to attributes.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'departmentId' => 'departamento',
        ];
    }
}
