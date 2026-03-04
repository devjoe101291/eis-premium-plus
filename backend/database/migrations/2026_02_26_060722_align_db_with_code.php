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
        // 1. eis_materials needs topic_id
        if (Schema::hasTable('eis_materials') && !Schema::hasColumn('eis_materials', 'topic_id')) {
            Schema::table('eis_materials', function (Blueprint $table) {
                $table->unsignedBigInteger('topic_id')->nullable()->after('id');
            });
        }

        // 2. eis_exams needs column alignments to match the Exam model
        if (Schema::hasTable('eis_exams')) {
            Schema::table('eis_exams', function (Blueprint $table) {
                // Rename or add fk_topic_id
                if (!Schema::hasColumn('eis_exams', 'fk_topic_id')) {
                    if (Schema::hasColumn('eis_exams', 'category_id')) {
                        $table->renameColumn('category_id', 'fk_topic_id');
                    }
                    else {
                        $table->unsignedBigInteger('fk_topic_id')->nullable()->after('id');
                    }
                }

                // Rename or add instructions
                if (!Schema::hasColumn('eis_exams', 'instructions')) {
                    if (Schema::hasColumn('eis_exams', 'description')) {
                        $table->renameColumn('description', 'instructions');
                    }
                    else {
                        $table->text('instructions')->nullable()->after('title');
                    }
                }

                // Rename or add passing_rate
                if (!Schema::hasColumn('eis_exams', 'passing_rate')) {
                    if (Schema::hasColumn('eis_exams', 'passing_score')) {
                        $table->renameColumn('passing_score', 'passing_rate');
                    }
                    else {
                        $table->decimal('passing_rate', 5, 2)->nullable()->after('time_limit');
                    }
                }

                // Add json columns
                if (!Schema::hasColumn('eis_exams', 'question_json')) {
                    // some older DB versions might not support JSON column types dynamically, but typically Text works too. We'll try json or text.
                    $table->json('question_json')->nullable()->after('passing_rate');
                }
                if (!Schema::hasColumn('eis_exams', 'answers_json')) {
                    $table->json('answers_json')->nullable()->after('question_json');
                }
            });

            // Handle status manually (swap 1 and 0 logic from is_active)
            if (!Schema::hasColumn('eis_exams', 'status') && Schema::hasColumn('eis_exams', 'is_active')) {
                Schema::table('eis_exams', function (Blueprint $table) {
                    $table->renameColumn('is_active', 'status');
                });

                // Active was 1, inactive 0. Now status 0 is active, 1 inactive.
                DB::statement('UPDATE eis_exams SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END');
            }
            elseif (!Schema::hasColumn('eis_exams', 'status')) {
                Schema::table('eis_exams', function (Blueprint $table) {
                    $table->tinyInteger('status')->default(0)->after('time_limit'); // 0 active, 1 inactive
                });
            }

            // Just double checking if answers_json is sometimes called answer_json in some contexts, we can add a fallback alias
            if (!Schema::hasColumn('eis_exams', 'answer_json')) {
                Schema::table('eis_exams', function (Blueprint $table) {
                    // Added as backup since the model might check `answer_json` in the JSON fallbacks
                    $table->json('answer_json')->nullable()->after('answers_json');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    // Revert columns if needed
    }
};
