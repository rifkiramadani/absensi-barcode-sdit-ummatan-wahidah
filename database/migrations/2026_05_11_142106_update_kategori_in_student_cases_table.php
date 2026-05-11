<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_cases', function (Blueprint $table) {
            // Ubah enum kategori — tambah opsi baru
            DB::statement("ALTER TABLE student_cases MODIFY COLUMN kategori ENUM(
                'Pelanggaran',
                'Prestasi Akademik',
                'Prestasi Non-Akademik',
                'Perilaku Baik',
                'Catatan Umum'
            ) NOT NULL DEFAULT 'Catatan Umum'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_cases', function (Blueprint $table) {
            DB::statement("ALTER TABLE student_cases MODIFY COLUMN kategori ENUM(
                'Pelanggaran',
                'Prestasi',
                'Lainnya'
            ) NOT NULL DEFAULT 'Pelanggaran'");
        });
    }
};
