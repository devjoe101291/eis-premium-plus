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
        Schema::table('eis_exams', function (Blueprint $table) {
            // Make optional string columns nullable so inserts without them don't fail
            if (Schema::hasColumn('eis_exams', 'exam_type')) {
                $table->string('exam_type')->nullable()->change();
            }
            if (Schema::hasColumn('eis_exams', 'description')) {
                $table->text('description')->nullable()->change();
            }
            if (Schema::hasColumn('eis_exams', 'passing_criteria_type')) {
                $table->string('passing_criteria_type')->nullable()->change();
            }
            if (Schema::hasColumn('eis_exams', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->change();
            }
            if (Schema::hasColumn('eis_exams', 'passing_score')) {
                $table->decimal('passing_score', 8, 2)->nullable()->change();
            }
            if (Schema::hasColumn('eis_exams', 'time_limit')) {
                $table->integer('time_limit')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed — making columns nullable is a safe forward-only change
    }
};
