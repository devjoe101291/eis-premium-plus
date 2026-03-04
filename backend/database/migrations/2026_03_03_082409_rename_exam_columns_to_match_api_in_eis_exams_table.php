<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename eis_exams columns to match the API contract used by the frontend:
     *   description   → instructions
     *   passing_score → passing_rate
     *   is_active     → status  (convention: 0 = Active, 1 = Inactive)
     *
     * Because the old is_active column used 1 = Active / 0 = Inactive (boolean),
     * we invert the values after renaming so that the new status column follows
     * the 0 = Active / 1 = Inactive convention expected by the frontend and
     * the ExamController mapping (is_active: true → status: 0).
     */
    public function up(): void
    {
        Schema::table('eis_exams', function (Blueprint $table) {
            // 1. description → instructions
            if (
                Schema::hasColumn('eis_exams', 'description') &&
                !Schema::hasColumn('eis_exams', 'instructions')
            ) {
                $table->renameColumn('description', 'instructions');
            }

            // 2. passing_score → passing_rate
            if (
                Schema::hasColumn('eis_exams', 'passing_score') &&
                !Schema::hasColumn('eis_exams', 'passing_rate')
            ) {
                $table->renameColumn('passing_score', 'passing_rate');
            }

            // 3. is_active → status
            if (
                Schema::hasColumn('eis_exams', 'is_active') &&
                !Schema::hasColumn('eis_exams', 'status')
            ) {
                $table->renameColumn('is_active', 'status');
            }
        });

        // 4. Invert status values:
        //    Old is_active: 1 = Active, 0 = Inactive
        //    New status:    0 = Active, 1 = Inactive
        if (Schema::hasColumn('eis_exams', 'status')) {
            DB::statement('UPDATE eis_exams SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END');

            // Update the column default to 0 (Active)
            Schema::table('eis_exams', function (Blueprint $table) {
                $table->tinyInteger('status')->default(0)->comment('0 = Active, 1 = Inactive')->change();
            });
        }
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('eis_exams', function (Blueprint $table) {
            // Restore instructions → description
            if (
                Schema::hasColumn('eis_exams', 'instructions') &&
                !Schema::hasColumn('eis_exams', 'description')
            ) {
                $table->renameColumn('instructions', 'description');
            }

            // Restore passing_rate → passing_score
            if (
                Schema::hasColumn('eis_exams', 'passing_rate') &&
                !Schema::hasColumn('eis_exams', 'passing_score')
            ) {
                $table->renameColumn('passing_rate', 'passing_score');
            }

            // Restore status → is_active
            if (
                Schema::hasColumn('eis_exams', 'status') &&
                !Schema::hasColumn('eis_exams', 'is_active')
            ) {
                $table->renameColumn('status', 'is_active');
            }
        });

        // Re-invert values back to is_active convention (1 = Active, 0 = Inactive)
        if (Schema::hasColumn('eis_exams', 'is_active')) {
            DB::statement('UPDATE eis_exams SET is_active = CASE WHEN is_active = 0 THEN 1 ELSE 0 END');

            Schema::table('eis_exams', function (Blueprint $table) {
                $table->tinyInteger('is_active')->default(1)->change();
            });
        }
    }
};
