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
        $userAccount = Auth::user()->account;

        //Recupera todas as transferências realizadas pelo usuário autenticado
        $transfersMade = Transaction::where('account_id', $userAccount->id)->where('type', '!=', 'deposit')->get();
        //Recupera todas as transferências recebidas pelo usuário autenticado
        $transfersReceived = Transaction::where('destination_account_id', $userAccount->id)->get();

        return view('account.statement', compact('transfersMade', 'transfersReceived'));
    }
}
