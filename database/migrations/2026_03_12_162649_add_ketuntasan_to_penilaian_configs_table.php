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
        Schema::table('penilaian_configs', function (Blueprint $table) {
            $table->string('konsep_ketuntasan')->default('Tidak Range')->after('tp_ids');
            $table->integer('range_tuntas_min')->default(75)->after('konsep_ketuntasan');
            $table->integer('range_tuntas_max')->default(100)->after('range_tuntas_min');
            $table->integer('range_tidak_tuntas_min')->default(0)->after('range_tuntas_max');
            $table->integer('range_tidak_tuntas_max')->default(74)->after('range_tidak_tuntas_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_configs', function (Blueprint $table) {
            $table->dropColumn([
                'konsep_ketuntasan',
                'range_tuntas_min',
                'range_tuntas_max',
                'range_tidak_tuntas_min',
                'range_tidak_tuntas_max',
            ]);
        });
    }
};
