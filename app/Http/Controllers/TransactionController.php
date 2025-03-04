<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\http\Requests\DepositRequest;
use App\http\Requests\TransferRequest;
use App\http\Requests\ReverseTransactionRequest;

class TransactionController extends Controller
{
    /**
     * Exibe a página com o formulário de transfência
     */
    public function transfer()
    {
        $account = Auth::user()->account;

        //Formata a exibição dos valores para o formato de moeda (Real)
        $account['balance'] = number_format($account['balance'], 2, ',', '.');

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

        return view('transaction.deposit', compact('account'));
    }

    /**
     * Realiza e registra o depósito
     */
    public function makeDeposit(DepositRequest $request)
    {
        //Validação do input do formulário
        $request->validated();

        $amount = (float) $request->amount;

        // Inicia a transação
        DB::beginTransaction();

        try {
            //Cria o registro da transação
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
            $transaction = Transaction::create($transactionData);

            //Realiza depósito
            $userAccount = Auth::user()->account;
            $userAccount->update([
                'balance' => $userAccount->balance + $amount
            ]);

            // Confirma a transação
            DB::commit();

            return redirect()->route('account.dashboard')->with('success', 'O depósito foi realizada com sucesso!');
        } catch (\Exception $e) {
            // Reverte as mudanças em caso de erro
            DB::rollBack();

            return redirect()->route('transaction.deposit')->with('error', 'Ocorreu um erro. Tente novamente.');
        }
    }

    /**
     * Realiza e registra a transferência
     */
    public function makeTransfer(TransferRequest $request)
    {
        //Validação do input do formulário
        $request->validated();

        //Recupera usuário associado a conta destino de acordo com o identificador (cpf ou email)
        if ($request->identification == 'cpf') {
            $destinationUser = User::where('cpf', $request->userIdentifier)->first();
        } elseif ($request->identification == 'email') {
            $destinationUser = User::where('email', $request->userIdentifier)->first();
        }

        //Valida se o usuário está transferindo para si mesmo
        if($destinationUser == Auth::user()) {
            return redirect()->route('transaction.transfer')->with('error', 'Não é possível realizar transferencias para você mesmo!');
        }
    
        //Valida se a conta destino e usúario destino existem
        if (!$destinationUser || !$destinationUser->account) {
            return redirect()->route('transaction.transfer')->with('error', 'Conta destino não encontrada!');
        }

        //Recupera conta destino
        $destinationUserAccount = $destinationUser->account;
        //Recupera conta do usuário de origem
        $originUserAccount = Auth::user()->account;

        //Verifica se o usuário tem saldo suficiente para a transação
        $amount = (float) $request->amount;
        if($amount > $originUserAccount->balance) {
            return redirect()->route('transaction.transfer')->with('error', 'Saldo insuficiente!');
        }

        // Inicia a transação
        DB::beginTransaction();

        try {
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

            //Realiza a transferência
            $originUserAccount->update([
                'balance' => $originUserAccount->balance - $amount
            ]);
            $destinationUserAccount->update([
                'balance' => $destinationUserAccount->balance + $amount
            ]);

            // Confirma a transação (commits todas as mudanças)
            DB::commit();

            return redirect()->route('account.dashboard')->with('success', 'Sua transferência foi realizada com sucesso!');
        } catch (\Exception $e) {
            // Se algo der errado, reverte as mudanças feitas até aqui
            DB::rollBack();

            return redirect()->route('transaction.transfer')->with('error', 'Ocorreu um erro. Tente novamente.');
        }
    }

    /**
     *  Reverte a transação
     */
    public function reverseTransaction(ReverseTransactionRequest $request)
    {
        //Validação do input do formulário
        $request->validated();

        //Recupera a transação
        $transaction = Transaction::find($request->transactionId);

        //Verifica se a transação existe
        if(!$transaction) {
            return redirect()->route('account.statement')->with('error', 'Transação inexistente!');
        }

        //Verifica se a transação a ser revertida foi realizada pelo usuário que está autenticado
        if($transaction->origin_account_user_cpf != Auth::user()->cpf) {
            return redirect()->route('account.statement')->with('error', 'Transação não encontrada!');
        }

        //Verifica se a transação já está revertida
        if($transaction->status != 'approved') {
            return redirect()->route('account.statement')->with('error', 'Transação já revertida!');
        }

        $originAccount = Auth::user()->account;

        //Barra a reversão do depósito caso ela deixe a conta negativada
        if($originAccount->balance < $transaction->amount && $transaction->type == 'deposit') {
            return redirect()->route('account.statement')->with('error', 'Saldo insuficiênte para reversão!');
        }

        //Realiza a reversão do depósito
        if($transaction->type == 'deposit') {
            $originAccount->update([
                'balance' => $originAccount->balance - $transaction->amount,
                'available_for_withdrawal' => $originAccount->available_for_withdrawal + $transaction->amount
            ]);

            $transaction->update([
                'status' => 'canceled'
            ]);

            return redirect()->route('account.dashboard')->with('success', 'Depósito revertido com sucesso!');
        }

        $destinationAccount = Account::find($transaction->destination_account_id);

        //Barra a reversão caso a conta destino nao tenha saldo suficiênte
        if($destinationAccount->balance < $transaction->amount && $transaction->type = 'transfer') {
            return redirect()->route('account.statement')->with('error', 'Saldo insuficiênte da conta destino para reversão!');
        }

        //Realiza a reversão de transferência
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

            return redirect()->route('account.dashboard')->with('success', 'Transferência revertida com sucesso!');
        }
        
    }

}
