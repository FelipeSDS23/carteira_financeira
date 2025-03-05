<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    
    protected $fillable = [
        'account_id',
        'origin_account_user_name',
        'origin_account_user_cpf',
        'destination_account_id', 
        'destination_account_user_name',
        'destination_account_user_cpf',
        'amount', 
        'type', 
        'status'
    ];

    /**
     * Implementa relação 1 para muitos com a model Accounts / conta de origem da transação.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Recupera o usuário vinculado à conta de origem.
     */
    public function originAccountUser()
    {
        return $this->account->user ?? null; // Retorna null se não houver um usuário vinculado à conta
    }

    /**
     * Relacionamento com a conta de destino
     */
    public function destinationAccount()
    {
        return $this->belongsTo(Account::class, 'destination_account_id');
    }

    /**
     * Recupera o usuário vinculado à conta de destino.
     */
    public function destinationAccountUser()
    {
        return $this->destinationAccount->user; // Supondo que a conta tem um relacionamento com o usuário.
    }
}
