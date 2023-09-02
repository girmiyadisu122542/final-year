<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('author');
            $table->string('cover_page');
            $table->string('path');
            $table->integer('size');
            $table->integer('page_number');
            $table->string('format');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('abstract');
            $table->unsignedBigInteger('year_id');
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('advisor_id')->nullable();
            $table->foreign('advisor_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('year_id')->references('id')->on('years')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
