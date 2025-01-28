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
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('allocated_amount', 12, 2);
            $table->foreignId('installment_id')->constrained('installments');
            $table->foreignId('payment_id')->constrained('payments');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->comment('Tabla intermedia (installment_payment) Permite asignar un pago a una o varias cuotas (parcial o completamente).
        De este modo, si un cliente paga 45.000 y la cuota era 30.000,
        podemos aplicar 30.000 a la cuota actual y los 15.000 restantes a la siguiente.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
    }
};
