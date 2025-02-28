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
            // Dados do remetente
            $table->foreignId('account_id')->constrained()->onDelete('cascade'); // Chave estrangeira referenciando a conta de origem
            $table->string('origin_account_user_name'); // Nome do usuário associado a conta de origem
            $table->string('origin_account_user_cpf', 14); // CPF do usuário associado a conta de origem
            // Dados do destinatário
            $table->integer('destination_account_id'); // Id da conta de destino
            $table->string('destination_account_user_name'); // Nome do usuário associado a conta de destino
            $table->string('destination_account_user_cpf', 14); // CPF do usuário associado a conta de destino
            // Dados da transação
            $table->decimal('amount', 11, 2); // Valor da transação
            $table->enum('type', ['deposit', 'transfer']); // Tipo da transação (depósito ou transferência)
            $table->enum('status', ['pending', 'approved', 'canceled']); // Status da transação
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
            // $table->dropForeign(['destination_account_id']);
            $table->dropForeign(['account_id']);
        });
        
        Schema::dropIfExists('transactions');
    }
};
