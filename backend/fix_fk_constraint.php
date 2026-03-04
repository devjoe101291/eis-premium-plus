<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::table('eis_exams', function (Blueprint $table) {
    // We drop the old foreign key constraint. The name might be based on the old column
    // name 'category_id'. Usually Laravel names it {table}_{column}_foreign.
    // Let's use raw SQL to drop it safely if it exists, since sometimes Laravel migrations
    // struggle to drop constraints if they don't know the exact name.
    });

    // Check if the constraint exists
    $constraintQuery = DB::select("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = DATABASE() 
          AND TABLE_NAME = 'eis_exams' 
          AND REFERENCED_TABLE_NAME = 'eis_exam_categories'
    ");

    if (count($constraintQuery) > 0) {
        $constraintName = $constraintQuery[0]->CONSTRAINT_NAME;
        DB::statement("ALTER TABLE eis_exams DROP FOREIGN KEY " . $constraintName);
        echo "Dropped old foreign key constraint: $constraintName\n";
    }

    // Now, let's create the correct constraint to `eis_topic` if one doesn't exist already.
    $newConstraintQuery = DB::select("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = DATABASE() 
          AND TABLE_NAME = 'eis_exams' 
          AND REFERENCED_TABLE_NAME = 'eis_topic'
    ");

    if (count($newConstraintQuery) == 0) {
        // Ensure that any existing fk_topic_id values are actually in eis_topic
        // If they are not, we might need to set them to null or handle it.
        DB::statement("
            UPDATE eis_exams 
            SET fk_topic_id = NULL 
            WHERE fk_topic_id NOT IN (SELECT id FROM eis_topic)
        ");

        Schema::table('eis_exams', function (Blueprint $table) {
            $table->foreign('fk_topic_id')->references('id')->on('eis_topic')->onDelete('set null');
        });
        echo "Added new foreign key constraint fk_topic_id referencing eis_topic(id)\n";
    }
    else {
        echo "New constraint already exists.\n";
    }

    echo "Updated foreign key successfully!\n";
}
catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
