<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    
    protected $fillable = ['user_id', 'balance', 'credit_limit'];

    /**
     * Implementa relação 1 para muitos com a model Accounts.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
