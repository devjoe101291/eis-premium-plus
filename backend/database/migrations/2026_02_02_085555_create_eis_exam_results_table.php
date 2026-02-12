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
    Schema::create('eis_exam_results', function (Blueprint $table) {
        // Primary key
        $table->id();

        // Student who took the exam
        $table->foreignId('user_id')
            ->constrained('eis_users')
            ->cascadeOnDelete();

        // Exam reference
        $table->unsignedBigInteger('fk_exam_id');

        // Scores
        $table->integer('score')->default(0);
        $table->integer('total_item')->default(0);
        $table->integer('total_points')->default(0);

        // Student answers (JSON)
        $table->json('employee_answer')->nullable();

        // Status: 0 = Active, 1 = Inactive
        $table->tinyInteger('status')
            ->default(0)
            ->comment('0 = Active, 1 = Inactive');

        // Attempt tracking
        $table->integer('attempt_number')->default(1);

        // Timestamps related to exam taking
        $table->timestamp('taken_at')->nullable();
        $table->timestamp('finish_at')->nullable();

        // Admin review tracking
        $table->unsignedBigInteger('viewed_by_admin_id')->nullable();
        $table->timestamp('viewed_by_admin_at')->nullable();

        // Standard timestamps
        $table->timestamps();

        // Foreign key constraints
        $table->foreign('fk_exam_id')
            ->references('id')
            ->on('eis_exams')
            ->cascadeOnDelete();

        $table->foreign('viewed_by_admin_id')
            ->references('id')
            ->on('eis_users')
            ->nullOnDelete();

        // Indexes for performance
        $table->index('fk_exam_id');
        $table->index('user_id');
        $table->index('status');
        $table->index(['user_id', 'fk_exam_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('eis_exam_results');
}

};
