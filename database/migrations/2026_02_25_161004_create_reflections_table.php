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
        Schema::create('reflections', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_penilaian', ['Kinerja', 'Proyek']);
            $table->string('kategori_predikat'); // Contoh: 'Baik', 'Cukup'
            $table->text('kelebihan_siswa')->nullable();
            $table->text('aspek_ditingkatkan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reflections');
    }
};
