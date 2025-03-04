<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReverseTransactionRequest extends FormRequest
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
            'transactionId' => 'required|exists:transactions,id', // Verifica se existe um ID na tabela 'transactions'
        ];
    }

    /**
     * Mensagens de feedback.
     */
    public function messages()
    {
        return [
            'transactionId.required' => 'O campo transactionId é obrigatório.',
            'transactionId.exists' => 'O ID da transação não existe.',
        ];
    }
}
