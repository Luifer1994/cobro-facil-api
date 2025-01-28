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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('The name of the plan');
            $table->text('description')->nullable()->comment('The description of the plan');
            $table->float('price', 20, 2)->comment('The price of the plan');
            $table->integer('number_of_month')->comment('The number of months the plan will live');
            $table->boolean('is_active')->default(true)->comment('The status of the plan');
            $table->foreignId('user_id')->constrained()->comment('The user who created the plan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
