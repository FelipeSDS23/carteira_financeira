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
        $account = Auth::user()->account;

        //Formata a exibição dos valores para o formato de moeda (Real)
        $account['balance'] = number_format($account['balance'], 2, ',', '.');
        $account['credit_limit'] = number_format($account['credit_limit'], 2, ',', '.');

        return view('transaction.transfer', compact('account'));
    }

    /**
     * Exibe a página com o formulário de depósito
     */
    public function deposit()
    {
        $account = Auth::user()->account;

        //Formata a exibição dos valores para o formato de moeda (Real)
        $account['balance'] = number_format($account['balance'], 2, ',', '.');
        $account['credit_limit'] = number_format($account['credit_limit'], 2, ',', '.');

        return view('transaction.deposit', compact('account'));
    }

    /**
     * Realiza e registra o depósito
     */
    public function storeDeposit(Request $request)
    {
        $userAccount = Auth::user()->account;

        // //Verifica se o usuário autenticado possui uma conta
        // if(!$userAccount) {
        //     return redirect()->route('account.dashboard');
        // }

        //Prepara a quantia para inserção no banco
        $amount = str_replace(".", "", $request->amount);
        $amount = str_replace(",", ".", $amount);
        $amount = (float) $amount;

        //Realiza depósito
        $userAccount->update([
            'balance' => $userAccount->balance + $amount
        ]);

        //Dados da transação
        $transactionData = [
            'account_id' => Auth::user()->account->id,
            'origin_account_user_name' => 'Depósito',
            'origin_account_user_cpf' => Auth::user()->cpf,
            'destination_account_id' => Auth::user()->account->id,
            'destination_account_user_name' => Auth::user()->name,
            'destination_account_user_cpf' => Auth::user()->cpf,
            'amount' => $amount,
            'type' => 'deposit',
            'status' => 'approved'
        ];

        //Cria registro da transação
        $transaction = Transaction::create($transactionData);

        return redirect()->route('account.dashboard');
    }

    /**
     * Realiza e registra a transferência
     */
    public function storeTransfer(Request $request)
    {
        //Validações aqui
   
        //Recupera usuário associado a conta destino de acordo com o identificador (cpf ou email)
        if( $request->identification == "cpf") {
            $destinationUser = User::where('cpf', $request->userIdentifier)->first();
        }
        if( $request->identification == "email") {
            $destinationUser = User::where('email', $request->userIdentifier)->first();
        }

        //Valida se o usúario destino existe
        if(!$destinationUser) {
            return redirect()->route('account.dashboard');
        }

        //Recupera conta destino
        $destinationUserAccount = $destinationUser->account;

        //Valida se a conta destino existe
        if(!$destinationUserAccount) {
            return redirect()->route('account.dashboard');
        }

        //Recupera conta do usuário de origem
        $originUserAccount = Auth::user()->account;

        //Prepara a quantia para realizar calculo e para inserção no banco
        $amount = str_replace(".", "", $request->amount);
        $amount = str_replace(",", ".", $amount);
        $amount = (float) $amount;

        //Realiza a transferência
        $originUserAccount->update([
            'balance' => $originUserAccount->balance - $amount
        ]);
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
            'status' => 'approved'
        ];

        //Cria registro da transação
        $transaction = Transaction::create($transactionData);

        return redirect()->route('account.dashboard');
    }

    /**
     *  Reverte a transação
     */
    public function reverseTransaction(Request $request)
    {
        //Validações

        //Recupera a transação
        $transaction = Transaction::find($request->transactionId);

        //Verifica se a transação existe
        if(!$transaction) {
            return redirect()->route('account.dashboard');
        }

        //Verifica se a transação a ser revertida foi realizada pelo usuário que está autenticado
        if($transaction->origin_account_user_cpf != Auth::user()->cpf) {
            return redirect()->route('account.dashboard');
        }

        //Verifica se a transação já está revertida
        if($transaction->status != 'approved') {
            return redirect()->route('account.dashboard');
        }

        //Recupera conta origem e conta destino da transação
        $originAccount = Auth::user()->account;

        //Realiza a reversão de depósito
        if($transaction->type == 'deposit') {
            $originAccount->update([
                'balance' => $originAccount->balance - $transaction->amount
            ]);

            $transaction->update([
                'status' => 'canceled'
            ]);

            return redirect()->route('account.dashboard');
        }

        //Realiza a reversão de transferência
        $destinationAccount = Account::find($transaction->destination_account_id);
        if($transaction->type == 'transfer') {
            $destinationAccount->update([
                'balance' => $destinationAccount->balance - $transaction->amount
            ]);

            $originAccount->update([
                'balance' => $originAccount->balance + $transaction->amount
            ]);

            $transaction->update([
                'status' => 'canceled'
            ]);

            return redirect()->route('account.dashboard');
        }

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
