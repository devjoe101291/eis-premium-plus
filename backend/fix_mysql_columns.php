<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    if (Schema::hasColumn('eis_exams', 'exam_type')) {
        DB::statement('ALTER TABLE eis_exams MODIFY exam_type VARCHAR(255) NULL');
    }
    if (Schema::hasColumn('eis_exams', 'passing_criteria_type')) {
        DB::statement('ALTER TABLE eis_exams MODIFY passing_criteria_type VARCHAR(255) NULL');
    }
    if (Schema::hasColumn('eis_exams', 'created_by')) {
        DB::statement('ALTER TABLE eis_exams MODIFY created_by BIGINT UNSIGNED NULL');
    }
    echo "Updated nullability bounds successfully!";
}
catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
