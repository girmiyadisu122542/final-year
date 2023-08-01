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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('college_id');
            $table->unsignedBigInteger('admission_type_id');
            $table->unsignedBigInteger('study_level_id');
            $table->integer('duration');
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('admission_type_id')->references('id')->on('admission_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('study_level_id')->references('id')->on('study_levels')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
