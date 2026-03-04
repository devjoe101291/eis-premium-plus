<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // 1. Make result_id the auto_increment primary key (if it isn't already)
    // First, check if there's any primary key. If not, add it.
    $pkCheck = DB::select("SHOW KEYS FROM eis_exam_results WHERE Key_name = 'PRIMARY'");
    if (count($pkCheck) === 0) {
        DB::statement('ALTER TABLE eis_exam_results MODIFY result_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY');
        echo "Made result_id AUTO_INCREMENT PRIMARY KEY.\n";
    }
    else {
        // If it is primary but not auto_increment
        DB::statement('ALTER TABLE eis_exam_results MODIFY result_id INT(11) NOT NULL AUTO_INCREMENT');
        echo "Made result_id AUTO_INCREMENT.\n";
    }

    // 2. Make remaining legacy required columns nullable or give them defaults
    DB::statement("ALTER TABLE eis_exam_results MODIFY exam_data LONGTEXT NULL");
    DB::statement("ALTER TABLE eis_exam_results MODIFY answer_key LONGTEXT NULL");
    DB::statement("ALTER TABLE eis_exam_results MODIFY employee_data LONGTEXT NULL");
    DB::statement("ALTER TABLE eis_exam_results MODIFY time_limit VARCHAR(50) NULL");
    DB::statement("ALTER TABLE eis_exam_results MODIFY date_modified DATETIME NULL");

    echo "All legacy columns in eis_exam_results safely modified to allow NULLs.\n";
}
catch (\Exception $e) {
    echo "Error updating eis_exam_results schema: " . $e->getMessage() . "\n";
}
