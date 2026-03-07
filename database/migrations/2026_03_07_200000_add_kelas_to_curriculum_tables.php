<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['subjects', 'aspects', 'indicators', 'learning_outcomes', 'learning_objectives', 'scores', 'reflections'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->string('kelas')->nullable()->after('school_profile_id');
            });
        }
    }

    public function down(): void
    {
        $tables = ['subjects', 'aspects', 'indicators', 'learning_outcomes', 'learning_objectives', 'scores', 'reflections'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('kelas');
            });
        }
    }
};
