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
        // 1. Convert aspect_id (single) → aspect_ids (json array) on penilaian_configs
        if (!Schema::hasColumn('penilaian_configs', 'aspect_ids')) {
            Schema::table('penilaian_configs', function (Blueprint $table) {
                $table->json('aspect_ids')->nullable()->after('jenis_penilaian');
            });
        }

        // Migrate existing aspect_id data to aspect_ids
        if (Schema::hasColumn('penilaian_configs', 'aspect_id')) {
            DB::table('penilaian_configs')->whereNotNull('aspect_id')->whereNull('aspect_ids')->orderBy('id')->each(function ($config) {
                DB::table('penilaian_configs')
                    ->where('id', $config->id)
                    ->update(['aspect_ids' => json_encode([(int) $config->aspect_id])]);
            });

            Schema::table('penilaian_configs', function (Blueprint $table) {
                $table->dropColumn('aspect_id');
            });
        }

        // 2. Add cp_ids and tp_ids to scores table (tag scores with curriculum context)
        if (!Schema::hasColumn('scores', 'cp_ids')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->json('cp_ids')->nullable()->after('subject_id');
                $table->json('tp_ids')->nullable()->after('cp_ids');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn(['cp_ids', 'tp_ids']);
        });

        Schema::table('penilaian_configs', function (Blueprint $table) {
            $table->unsignedBigInteger('aspect_id')->nullable()->after('jenis_penilaian');
        });

        DB::table('penilaian_configs')->whereNotNull('aspect_ids')->orderBy('id')->each(function ($config) {
            $ids = json_decode($config->aspect_ids, true);
            if (!empty($ids)) {
                DB::table('penilaian_configs')
                    ->where('id', $config->id)
                    ->update(['aspect_id' => $ids[0]]);
            }
        });

        Schema::table('penilaian_configs', function (Blueprint $table) {
            $table->dropColumn('aspect_ids');
        });
    }
};
