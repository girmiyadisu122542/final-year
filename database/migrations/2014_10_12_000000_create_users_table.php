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
            $table->string('full_name');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('department_id');
            $table->string('address')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('code');
            $table->tinyInteger('status')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict')->onUpdate('cascade');
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
