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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the user');
            $table->string('email')->unique()->comment('Email of the user');
            $table->string('phone')->nullable()->comment('Phone of the user');
            $table->boolean('is_active')->default(true)->comment('Is the user active?');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->comment('Password of the user');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
