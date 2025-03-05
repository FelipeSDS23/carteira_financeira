<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Exibe a página inical (Minha área) da conta, contendo os detalhes da conta
     */
    public function dashboard()
    {
        $userAccount = Auth::user()->account;

        //Formata a exibição dos valores para o formato de moeda (Real)
        $userAccount['balance'] = number_format($userAccount['balance'], 2, ',', '.');
        
        return view('account.dashboard', compact('userAccount'));
    }

    /**
     * Exibe o extrato da conta
     */
    public function statement()
    {
        $user = Auth::user();
        $userAccount = $user->account;

        // Recupera todas as transações realizadas, recebidas e depósitos
        $transactions = Transaction::with(['account.user', 'destinationAccount.user'])
            ->where(function($query) use ($userAccount) {
                $query->where('account_id', $userAccount->id)
                    ->orWhere('destination_account_id', $userAccount->id);
            })->get();

        // Filtra as transferências feitas, recebidas e depósitos
        $transfersMade = $transactions->where('account_id', $userAccount->id)->where('type', '!=', 'deposit');
        $transfersReceived = $transactions->where('destination_account_id', $userAccount->id)->where('type', '!=', 'deposit');
        $deposits = $transactions->where('destination_account_id', $userAccount->id)->where('type', '=', 'deposit');

        return view('account.statement', compact('transfersMade', 'transfersReceived', 'deposits'));
    }

}
