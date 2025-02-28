<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Exibe a página com o formulário de transfência
     */
    public function transfer()
    {
        return view('transaction.transfer');
    }

    /**
     * Exibe a página com o formulário de depósito
     */
    public function deposit()
    {
        return view('transaction.deposit');
    }

    /**
     * Cria registro do depósito e exibi página para confirmação
     */
    public function storeTransaction(Request $request)
    {

        //Validações aqui
   

        //Recupera id da conta destino
        $destinationUser = User::where('cpf', $request->userIdentifier)->first();
        $destinationUserAccount = $destinationUser->account;

        //Prepara a quantia para inserção no banco
        $amount = str_replace(".", "", $request->amount);
        $amount = str_replace(",", ".", $amount);
        $amount = (float) $amount;

        //Cria registro da transação
        $transaction = Transaction::create([
            'account_id' => Auth::user()->account->id,
            'origin_account_user_name' => Auth::user()->name,
            'origin_account_user_cpf' => Auth::user()->cpf,
            
            'destination_account_id' => $destinationUserAccount->id,
            'destination_account_user_name' => $destinationUser->name,
            'destination_account_user_cpf' => $destinationUser->cpf,

            'amount' => $amount,
            'type' => 'transfer',
            'status' => 'pending'
        ]);

        $transactionData = [
            'destinatario' => $destinationUser->name,
            'cpf' => $destinationUser->cpf,
            'amount' => number_format($transaction->amount, 2, ',', '.')
        ];

        return view('transaction.confirmation', $transactionData);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
