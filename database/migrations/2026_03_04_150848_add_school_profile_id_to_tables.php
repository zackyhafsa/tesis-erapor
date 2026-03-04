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
        $tables = [
            'users',
            'students',
            'subjects',
            'aspects',
            'indicators',
            'learning_outcomes',
            'learning_objectives',
            'scores',
            'reflections',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // Tambahkan school_profile_id (nullable sementara agar data lama tidak error)
                $table->foreignId('school_profile_id')->nullable()->constrained('school_profiles')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'students',
            'subjects',
            'aspects',
            'indicators',
            'learning_outcomes',
            'learning_objectives',
            'scores',
            'reflections',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['school_profile_id']);
                $table->dropColumn('school_profile_id');
            });
        }
    }
};
