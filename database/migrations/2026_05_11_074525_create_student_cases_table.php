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
        Schema::create('student_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_kejadian');
            $table->enum('kategori', ['Pelanggaran', 'Prestasi', 'Lainnya'])->default('Pelanggaran');
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('tindak_lanjut')->nullable();
            $table->string('dicatat_oleh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_cases');
    }
};
