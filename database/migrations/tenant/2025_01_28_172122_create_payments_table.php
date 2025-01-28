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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2)->comment(' Monto pagado');
            $table->enum('payment_type', ['normal', 'additional'])->default('normal')
                ->comment(' -- normal = pago esperado de la cuota -- additional = abono extra a capital (ej. abono para reducir la deuda)');
            $table->enum('allocated_to', ['capital', 'interest', 'both'])->default('both')
                ->comment('Indica cómo se distribuye este pago normalmente -- capital = capital -- interest = interés -- both = capital y interés');
            $table->foreignId('loan_id')->constrained('loans');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->comment('Tabla de pagos Registra cada pago realizado, ya sea total, parcial, adicional, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
