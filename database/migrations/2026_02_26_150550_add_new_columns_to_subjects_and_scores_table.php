<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah Capaian & Tujuan di tabel Mata Pelajaran
        Schema::table('subjects', function (Blueprint $table) {
            $table->text('capaian_pembelajaran')->nullable();
            $table->text('tujuan_pembelajaran')->nullable();
        });

        // 2. Tambah Catatan Guru di tabel Nilai
        Schema::table('scores', function (Blueprint $table) {
            $table->string('catatan_guru')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['capaian_pembelajaran', 'tujuan_pembelajaran']);
        });
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn('catatan_guru');
        });
    }
};
