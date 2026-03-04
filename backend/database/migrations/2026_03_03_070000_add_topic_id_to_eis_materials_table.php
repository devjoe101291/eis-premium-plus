<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ensures `topic_id` exists in eis_materials.
     * - If `fk_topic_id` exists and `topic_id` does not → rename it.
     * - If neither exists → add `topic_id` as a proper FK to eis_topic.
     * - If `topic_id` already exists → skip (idempotent).
     */
    public function up(): void
    {
        Schema::table('eis_materials', function (Blueprint $table) {
            $hasTopic    = Schema::hasColumn('eis_materials', 'topic_id');
            $hasFkTopic  = Schema::hasColumn('eis_materials', 'fk_topic_id');

            if ($hasTopic) {
                // Already correct – nothing to do
                return;
            }

            if ($hasFkTopic) {
                // Rename fk_topic_id → topic_id
                $table->renameColumn('fk_topic_id', 'topic_id');
                return;
            }

            // Column is completely missing – add it
            $table->unsignedBigInteger('topic_id')->nullable()->after('id');
            $table->foreign('topic_id')
                  ->references('id')
                  ->on('eis_topic')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eis_materials', function (Blueprint $table) {
            if (Schema::hasColumn('eis_materials', 'topic_id')) {
                try {
                    $table->dropForeign(['topic_id']);
                } catch (\Throwable $e) {
                    // No FK constraint – safe to ignore
                }
                $table->dropColumn('topic_id');
            }
        });
    }
};
