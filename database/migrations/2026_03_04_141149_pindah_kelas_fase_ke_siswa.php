<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom kelas dan fase ke tabel students
        Schema::table('students', function (Blueprint $table) {
            $table->string('kelas')->nullable()->after('nama');
            $table->string('fase')->nullable()->after('kelas');
        });

        // 2. Hapus kolom kelas dan fase dari tabel school_profiles
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'fase']);
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'fase']);
        });

        Schema::table('school_profiles', function (Blueprint $table) {
            $table->string('kelas')->nullable();
            $table->string('fase')->nullable();
        });
    }
};
