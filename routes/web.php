<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('auth.login');
    // return view('welcome');
});

Route::get('/dashboard', function () {
    // return view('dashboard');
    return redirect()->route('account.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('transaction')->middleware('auth')->group(function () {
    Route::get('/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer');
    Route::get('/deposit', [TransactionController::class, 'deposit'])->name('transaction.deposit');
    Route::post('/deposit', [TransactionController::class, 'storeTransaction'])->name('transaction.store');
    Route::post('/reverse', [TransactionController::class, 'reverseTransaction'])->name('transaction.reverse');
});

Route::prefix('account')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/statement', [AccountController::class, 'statement'])->name('account.statement');
});

require __DIR__.'/auth.php';
