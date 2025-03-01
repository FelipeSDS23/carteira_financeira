<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id', 'balance', 'credit_limit'];

    /**
     * Implementa relação 1 para 1 com a model User.
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Implementa relação 1 para muitos com a model Transaction.
     *
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
