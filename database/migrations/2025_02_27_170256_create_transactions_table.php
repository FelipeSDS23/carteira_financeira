<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade'); // Chave estrangeira para a tabela accounts / Conta de origem
            // $table->foreignId('origin_account_id')->nullable()->constrained('accounts')->onDelete('set null'); // Conta de origem
            $table->foreignId('destination_account_id')->nullable()->constrained('accounts')->onDelete('set null'); // Conta de destino
            $table->decimal('amount', 11, 2); // Valor da transação
            $table->enum('type', ['deposit', 'transfer']); // Tipo da transação (depósito ou transferência)
            $table->enum('status', ['pending', 'approved', 'canceled']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // $table->dropForeign(['origin_account_id']);
            $table->dropForeign(['destination_account_id']);
            $table->dropForeign(['account_id']);
        });
        
        Schema::dropIfExists('transactions');
    }
};
