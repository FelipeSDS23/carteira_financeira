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
     * Exibe o extrato da conta
     */
    public function statement()
    {
        $user = Auth::user();
        $userAccount = Auth::user()->account;

        //Recupera todas as transferências feitas pelo usuário autenticado
        $transfersMade = Transaction::where('account_id', $userAccount->id)->get();
        //Recupera todas as transferências recebidas pelo usuário autenticado
        $transfersReceived = Transaction::where('destination_account_id', $userAccount->id)->get();

        // dd($transfersReceived[0]);

        return view('account.statement', compact('transfersMade', 'transfersReceived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
