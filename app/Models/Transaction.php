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
     * Implementa relação 1 para muitos com a model Accounts.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Defina a relação com a conta de destino
     */
    // public function destinationAccount()
    // {
    //     return $this->belongsTo(Account::class, 'destination_account_id');
    // }

    /**
     * Método para acessar o usuário relacionado à conta de destino
     */
    // public function destinationUser()
    // {
    //     return $this->destinationAccount->user();
    // }
}
