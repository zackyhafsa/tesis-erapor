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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspect_id')->constrained('aspects')->cascadeOnDelete();
            $table->string('nama_indikator');
            $table->text('deskripsi_kriteria')->nullable();
            $table->text('catatan_skor_1')->nullable(); // Rubrik: Perlu Bimbingan
            $table->text('catatan_skor_2')->nullable(); // Rubrik: Cukup
            $table->text('catatan_skor_3')->nullable(); // Rubrik: Baik
            $table->text('catatan_skor_4')->nullable(); // Rubrik: Sangat Baik
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
