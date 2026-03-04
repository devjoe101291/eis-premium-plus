<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 1. Adds missing question_json and answers_json columns to eis_exams.
     * 2. Drops category_id (old topic FK integer) and adds category (string for exam type).
     * 3. Adds topic_id as the proper FK to eis_topic.
     * 4. Drops legacy orphan tables: eis_exam_answers, eis_exam_attempts,
     *    eis_exam_categories, eis_exam_questions.
     */
    public function up(): void
    {
        // 1. Alter eis_exams - add new columns
        Schema::table('eis_exams', function (Blueprint $table) {

            // Add topic_id FK (replaces category_id as the topic reference)
            if (!Schema::hasColumn('eis_exams', 'topic_id')) {
                $table->unsignedBigInteger('topic_id')->nullable();
                $table->foreign('topic_id')
                      ->references('id')
                      ->on('eis_topic')
                      ->cascadeOnDelete();
                $table->index('topic_id');
            }

            // Add question_json
            if (!Schema::hasColumn('eis_exams', 'question_json')) {
                $table->json('question_json')->nullable();
            }

            // Add answers_json
            if (!Schema::hasColumn('eis_exams', 'answers_json')) {
                $table->json('answers_json')->nullable();
            }

            // Add category string - stores exam type: multiple, multiple-answer, true-false, short
            if (!Schema::hasColumn('eis_exams', 'category')) {
                $table->string('category', 50)->nullable();
            }
        });

        // 2. Drop old category_id column (replaced by topic_id + category string)
        Schema::table('eis_exams', function (Blueprint $table) {
            if (Schema::hasColumn('eis_exams', 'category_id')) {
                try {
                    $table->dropForeign(['category_id']);
                } catch (\Throwable $e) {
                    // No FK constraint on category_id - safe to ignore
                }
                $table->dropColumn('category_id');
            }
        });

        // 3. Drop legacy orphan tables (disable FK checks to avoid constraint errors)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('eis_exam_answers');
        Schema::dropIfExists('eis_exam_attempts');
        Schema::dropIfExists('eis_exam_questions');
        Schema::dropIfExists('eis_exam_categories');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eis_exams', function (Blueprint $table) {
            // Restore category_id
            if (!Schema::hasColumn('eis_exams', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('id');
            }

            // Remove topic_id FK and column
            if (Schema::hasColumn('eis_exams', 'topic_id')) {
                try {
                    $table->dropForeign(['topic_id']);
                    $table->dropIndex(['topic_id']);
                } catch (\Throwable $e) {
                    // ignore
                }
                $table->dropColumn('topic_id');
            }

            // Remove added columns
            foreach (['question_json', 'answers_json', 'category'] as $col) {
                if (Schema::hasColumn('eis_exams', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
