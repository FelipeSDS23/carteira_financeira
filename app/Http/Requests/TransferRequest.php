<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CpfValidation;
use Illuminate\Validation\Validator;

class TransferRequest extends FormRequest
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
        $rules = [
            'amount' => 'required|numeric',
            'identification' => 'required|in:cpf,email',
            'userIdentifier' => 'required', // Validação básica, será substituída conforme o identification
        ];

        // Se a identificação for CPF, aplica a validação personalizada
        if ($this->input('identification') === 'cpf') {
            $rules['userIdentifier'] = ['required', new CpfValidation()];
        }

        // Se a identificação for e-mail, aplica a validação de e-mail
        if ($this->input('identification') === 'email') {
            $rules['userIdentifier'] = 'required|email';
        }

        return $rules;
    }

    /**
     * Mensagens de feedback.
     */
    public function messages()
    {
        return [
            'amount.required' => 'O campo valor é obrigatório.',
            'amount.numeric' => 'Valor inválido. Tente novamente.',
            'identification.required' => 'Tipo de identificador inválido',
            'userIdentifier.required' => 'O campo CPF/E-mail é obrigatório.',
        ];
    }

    /**
     * Modifica os dados antes da validação.
     * Formata moeda para realizar operações matemáticas ou armazenar no banco de dados Ex: 1.000,00 para 1000.00
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'amount' => str_replace(',', '.', str_replace('.', '', $this->amount)),
        ]);
    }

}
