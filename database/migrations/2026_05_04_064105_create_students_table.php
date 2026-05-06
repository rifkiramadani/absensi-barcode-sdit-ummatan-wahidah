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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->enum('gender', ['L', 'P']); // L = laki-laki, P = perempuan
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('nik')->unique();
            $table->string('nisn')->unique();
            $table->year('entry_year');

            $table->string('photo')->nullable();

            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();

            $table->string('rfid_uid')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
