<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Renames `topic_id` → `fk_topic_id` in eis_exams if the old column exists.
     * Safe to run even if the column was already named correctly.
     */
    public function up(): void
    {
        Schema::table('eis_exams', function (Blueprint $table) {
            // Only rename if the old column exists and the new one does NOT
            if (
                Schema::hasColumn('eis_exams', 'topic_id') &&
                !Schema::hasColumn('eis_exams', 'fk_topic_id')
            ) {
                $table->renameColumn('topic_id', 'fk_topic_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eis_exams', function (Blueprint $table) {
            if (
                Schema::hasColumn('eis_exams', 'fk_topic_id') &&
                !Schema::hasColumn('eis_exams', 'topic_id')
            ) {
                $table->renameColumn('fk_topic_id', 'topic_id');
            }
        });
    }
};
