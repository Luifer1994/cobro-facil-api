<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('document_number', 20);
            $table->string('address')->nullable();
            $table->foreignId('city_id')->constrained('cities');
            $table->string('cell_phone', 20)->nullable();
            $table->string('email')->required()->unique();
            $table->string('logo')->nullable();
            $table->char('primary_color', 10)->nullable();
            $table->char('secondary_color', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->unique(['document_type_id', 'document_number']);
            $table->foreignId('user_created_id')->constrained('users');
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
