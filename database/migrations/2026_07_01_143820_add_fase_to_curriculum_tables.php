<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'subjects',
            'aspects',
            'indicators',
            'reflections',
            'learning_objectives',
            'learning_outcomes'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('fase')->nullable()->after('kelas');
            });

            // Update existing data for each table
            DB::table($tableName)->update([
                'fase' => DB::raw("CASE
                    WHEN CAST(kelas AS UNSIGNED) >= 1 AND CAST(kelas AS UNSIGNED) <= 2 THEN 'A'
                    WHEN CAST(kelas AS UNSIGNED) >= 3 AND CAST(kelas AS UNSIGNED) <= 4 THEN 'B'
                    WHEN CAST(kelas AS UNSIGNED) >= 5 AND CAST(kelas AS UNSIGNED) <= 6 THEN 'C'
                    WHEN CAST(kelas AS UNSIGNED) >= 7 AND CAST(kelas AS UNSIGNED) <= 9 THEN 'D'
                    ELSE NULL
                END")
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'subjects',
            'aspects',
            'indicators',
            'reflections',
            'learning_objectives',
            'learning_outcomes'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('fase');
            });
        }
    }
};
