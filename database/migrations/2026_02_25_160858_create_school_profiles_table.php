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
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            // Identitas Sekolah
            $table->string('nama_sekolah')->nullable();
            $table->string('npsn')->nullable();
            $table->string('nss')->nullable();
            
            // Alamat
            $table->string('alamat')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            
            // Info Kelas & Akademik
            $table->string('kelas')->nullable(); // Contoh: I (Satu)
            $table->string('fase')->nullable();  // Contoh: A
            $table->string('semester')->nullable(); // Contoh: I (Satu)
            $table->string('tahun_pelajaran')->nullable();
            
            // Penandatangan & Titik Mangsa
            $table->string('kepala_sekolah')->nullable();
            $table->string('nip_kepsek')->nullable();
            $table->string('guru_kelas')->nullable();
            $table->string('nip_guru')->nullable();
            $table->string('tempat_cetak')->nullable(); // Contoh: Kertajati
            $table->date('tanggal_cetak')->nullable();  // Tanggal rapor dibagikan
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
