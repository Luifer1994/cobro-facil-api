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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->date('due_date')->comment('Fecha de vencimiento');
            $table->decimal('expected_amount', 12, 2)->comment('Monto que se espera cobrar en esta cuota (capital + interés estimado)');
            $table->decimal('capital_balance', 12, 2)->comment('Cuánto capital corresponde a esta cuota');
            $table->decimal('interest_balance', 12, 2)->comment('Cuánto interés corresponde a esta cuota');
            $table->boolean('is_paid')->default(false);
            $table->foreignId('loan_id')->constrained('loans');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->comment('Tabla de cuotas Aquí se definen las cuotas programadas: fecha de vencimiento, montos, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
