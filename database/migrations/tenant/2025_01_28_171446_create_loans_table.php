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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2)->comment('Monto del préstamo');
            $table->decimal('interest_rate', 5, 2)->comment('Porcentaje de interés');
            $table->enum('interest_type', ['fixed','reducing'])->comment('Aquí se define si el interes es fijo o reduciendo');
            $table->enum('payment_frequency', ['daily','weekly','biweekly','monthly'])->comment('Aquí se define la frecuencia de pago');
            $table->integer('installments_count')->comment('Número de cuotas');
            $table->date('start_date')->comment('Fecha de inicio');
            $table->decimal('outstanding_balance', 12, 2)->default(0)->comment('Saldo pendiente, este se debe recalcular  que se registre un pago');
            $table->enum('status', ['active','finished','defaulted'])->default('active')->comment('Estado de la deuda');
            $table->foreignId('client_id')->constrained('clients')->comment('Cliente al que se le debe el préstamo');
            $table->foreignId('user_id')->constrained('users')->comment('Usuario que creó la deuda');
            $table->timestamps();
            $table->comment('Tabla de préstamos Configuración general del préstamo, su tipo de interés, montos, etc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
