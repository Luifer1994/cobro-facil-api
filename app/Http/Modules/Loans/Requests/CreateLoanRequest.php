<?php

namespace App\Http\Modules\Loans\Requests;

use App\Http\Bases\BaseFormRequest;

class CreateLoanRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'interest_rate' => 'required|numeric|min:0.01',
            'interest_type' => 'required|in:fixed,reducing',
            'payment_frequency' => 'required|in:daily,weekly,biweekly,monthly',
            'installments_count' => 'required|numeric|min:1',
            'status' => 'nullable|in:active,finished,defaulted',
            'client_id' => 'required|numeric|min:1|exists:clients,id',
            'description' => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'El monto del préstamo es obligatorio',
            'amount.numeric' => 'El monto del préstamo debe ser numérico',
            'amount.min' => 'El monto del préstamo debe ser mayor a 0.01',
            'interest_rate.required' => 'El porcentaje de interés es obligatorio',
            'interest_rate.numeric' => 'El porcentaje de interés debe ser numérico',
            'interest_rate.min' => 'El porcentaje de interés debe ser mayor a 0.01',
            'interest_type.required' => 'El tipo de interés es obligatorio',
            'interest_type.in' => 'El tipo de interés debe ser fijo o reduciendo',
            'payment_frequency.required' => 'La frecuencia de pago es obligatorio',
            'payment_frequency.in' => 'La frecuencia de pago debe ser diaria, semanal, bimensual o mensual',
            'installments_count.required' => 'El número de cuotas es obligatorio',
            'installments_count.numeric' => 'El número de cuotas debe ser numérico',
            'installments_count.min' => 'El número de cuotas debe ser mayor a 1',
            'status.nullable' => 'El estado es obligatorio',
            'status.in' => 'El estado debe ser activo, finalizado o default',
            'client_id.required' => 'El cliente es obligatorio',
            'client_id.numeric' => 'El cliente debe ser numérico',
            'client_id.min' => 'El cliente debe ser mayor a 1',
            'client_id.exists' => 'El cliente seleccionado no existe',
            'description.nullable' => 'La descripción es obligatoria',
            'description.string' => 'La descripción debe ser una cadena de caracteres',
            'description.max' => 'La descripción debe tener un máximo de 255 caracteres'
        ];
    }
}
