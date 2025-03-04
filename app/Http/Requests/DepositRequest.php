<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric'
        ];
    }

    /**
     * Mensagens de feedback.
     */
    public function messages()
    {
        return [
            'amount.required' => 'O campo valor é obrigatório.',
            'amount.numeric' => 'Valor inválido. Tente novamente.'
        ];
    }

    /**
     * Modifica os dados antes da validação.
     * Formata moeda para realizar operações matemáticas ou armazenar no banco de dados Ex: 1.000,00 para 1000.00
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'amount' => str_replace(',', '.', str_replace('.', '', $this->amount)), //Converte o valor do campo amount antes de validá-lo, convertendo-o do formato brasileiro (100,00) para o formato internacional (100.00).
        ]);
    }

}
