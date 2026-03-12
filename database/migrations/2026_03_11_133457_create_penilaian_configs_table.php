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
        Schema::create('penilaian_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->string('jenis_penilaian')->nullable();
            $table->unsignedBigInteger('aspect_id')->nullable();
            $table->json('cp_ids')->nullable();
            $table->json('tp_ids')->nullable();
            $table->timestamps();

            $table->unique(['school_profile_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_configs');
    }
};
