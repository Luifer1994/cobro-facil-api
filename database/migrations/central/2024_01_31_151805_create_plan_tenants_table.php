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
        Schema::create('plan_tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->comment('The plan that is being rented');
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->comment('The tenant who is renting the plan');
            $table->date('start_date')->comment('The date the plan was rented');
            $table->date('end_date')->comment('The date the plan will be returned');
            $table->float('price', 20, 2)->comment('The price of the plan');
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
        Schema::dropIfExists('plan_tenants');
    }
};
