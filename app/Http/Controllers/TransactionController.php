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
   

        //Recupera usuário associado a conta destino
        $destinationUser = User::where('cpf', $request->userIdentifier)->first();
        //Recupera conta destino
        $destinationUserAccount = $destinationUser->account;

        //Recupera conta do usuário de origem
        $originUserAccount = Auth::user()->account;




        //Prepara a quantia para inserção no banco
        $amount = str_replace(".", "", $request->amount);
        $amount = str_replace(",", ".", $amount);
        $amount = (float) $amount;



        //Realiza a transferência

        //Remove valor da conta origem
        $originUserAccount->update([
            'balance' => $originUserAccount->balance - $amount
        ]);

        //Adiciona valor da conta destino
        $destinationUserAccount->update([
            'balance' => $destinationUserAccount->balance + $amount
        ]);


        //Dados da transação
        $transactionData = [
            'account_id' => Auth::user()->account->id,
            'origin_account_user_name' => Auth::user()->name,
            'origin_account_user_cpf' => Auth::user()->cpf,
            'destination_account_id' => $destinationUserAccount->id,
            'destination_account_user_name' => $destinationUser->name,
            'destination_account_user_cpf' => $destinationUser->cpf,
            'amount' => $amount,
            'type' => 'transfer',
            'status' => 'pending'
        ];

        //Cria registro da transação
        $transaction = Transaction::create($transactionData);

        $transaction['amount'] = number_format($transaction['amount'], 2, ',', '.');

        return redirect()->route('account.dashboard');

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
