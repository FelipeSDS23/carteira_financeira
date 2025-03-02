<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return redirect()->route('account.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('transaction')->middleware('auth')->group(function () {
    Route::get('/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer'); //Mostra página que realiza transferências
    Route::get('/deposit', [TransactionController::class, 'deposit'])->name('transaction.deposit'); //Mostra página que realiza depósitos
    Route::post('/transfer', [TransactionController::class, 'makeTransfer'])->name('transaction.transfer'); //Realiza a transferência de saldo
    Route::post('/deposit', [TransactionController::class, 'makeDeposit'])->name('transaction.deposit'); //Realiza o depósito de saldo
    Route::post('/reverse', [TransactionController::class, 'reverseTransaction'])->name('transaction.reverse'); //Reverte uma transação
});

Route::prefix('account')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('account.dashboard'); //Mostra página inicial da conta
    Route::get('/statement', [AccountController::class, 'statement'])->name('account.statement'); //Mostra página com o extrato da conta
});

require __DIR__.'/auth.php';
