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

}
