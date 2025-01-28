<?php

namespace App\Http\Bases;


class BasePaginateRequest extends BaseFormRequest
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
            'limit'            => 'nullable|numeric:max:50',
            'search'           => 'nullable|string',
        ];
    }

    /**
     * Get the attributes that are mass assignable.
     *
     * @return array<int, string>
     */
    public function attributes()
    {
        return [
            'limit'  => 'límite',
            'search' => 'búsqueda',
        ];
    }
}
