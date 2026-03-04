<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    Schema::table('eis_exam_results', function (Blueprint $table) {
        if (!Schema::hasColumn('eis_exam_results', 'user_id')) {
            $table->unsignedBigInteger('user_id')->nullable();
        }
        if (!Schema::hasColumn('eis_exam_results', 'score')) {
            $table->decimal('score', 8, 2)->nullable();
        }
        if (!Schema::hasColumn('eis_exam_results', 'total_points')) {
            $table->integer('total_points')->nullable();
        }
        if (!Schema::hasColumn('eis_exam_results', 'employee_answer')) {
            $table->json('employee_answer')->nullable();
        }
        if (!Schema::hasColumn('eis_exam_results', 'taken_at')) {
            $table->timestamp('taken_at')->nullable();
        }
        if (!Schema::hasColumn('eis_exam_results', 'finish_at')) {
            $table->timestamp('finish_at')->nullable();
        }
        // Also add created_at, updated_at if they don't exist
        if (!Schema::hasColumn('eis_exam_results', 'created_at')) {
            $table->timestamp('created_at')->nullable();
        }
        if (!Schema::hasColumn('eis_exam_results', 'updated_at')) {
            $table->timestamp('updated_at')->nullable();
        }
        // status column could also be missing if they used result_status
        if (!Schema::hasColumn('eis_exam_results', 'status')) {
            $table->tinyInteger('status')->default(0);
        }
    });

    // Make old required columns nullable so inserts don't fail for them either
    if (Schema::hasColumn('eis_exam_results', 'fk_employee_id')) {
        DB::statement('ALTER TABLE eis_exam_results MODIFY fk_employee_id BIGINT UNSIGNED NULL');
    }
    if (Schema::hasColumn('eis_exam_results', 'total_score')) {
        DB::statement('ALTER TABLE eis_exam_results MODIFY total_score INT NULL');
    }
    if (Schema::hasColumn('eis_exam_results', 'employee_score')) {
        DB::statement('ALTER TABLE eis_exam_results MODIFY employee_score INT NULL');
    }
    if (Schema::hasColumn('eis_exam_results', 'passing_rate')) {
        DB::statement('ALTER TABLE eis_exam_results MODIFY passing_rate VARCHAR(255) NULL');
    }
    if (Schema::hasColumn('eis_exam_results', 'result_status')) {
        DB::statement('ALTER TABLE eis_exam_results MODIFY result_status VARCHAR(255) NULL');
    }

    echo "Exam Results Schema fully synchronized to modern syntax successfully!\n";
}
catch (\Exception $e) {
    echo "Error updating eis_exam_results schema: " . $e->getMessage() . "\n";
}
