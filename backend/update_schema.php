<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// 1. eis_materials needs topic_id
if (Schema::hasTable('eis_materials') && !Schema::hasColumn('eis_materials', 'topic_id')) {
    Schema::table('eis_materials', function (Blueprint $table) {
        $table->unsignedBigInteger('topic_id')->nullable()->after('id');
    });
    echo "Added topic_id to eis_materials.\n";
}

// 2. eis_exams needs column alignments
if (Schema::hasTable('eis_exams')) {
    Schema::table('eis_exams', function (Blueprint $table) {
        if (!Schema::hasColumn('eis_exams', 'fk_topic_id')) {
            if (Schema::hasColumn('eis_exams', 'category_id')) {
                $table->renameColumn('category_id', 'fk_topic_id');
                echo "Renamed category_id to fk_topic_id in eis_exams.\n";
            }
            else {
                $table->unsignedBigInteger('fk_topic_id')->nullable()->after('id');
                echo "Added fk_topic_id to eis_exams.\n";
            }
        }
    });

    Schema::table('eis_exams', function (Blueprint $table) {
        if (!Schema::hasColumn('eis_exams', 'instructions')) {
            if (Schema::hasColumn('eis_exams', 'description')) {
                $table->renameColumn('description', 'instructions');
                echo "Renamed description to instructions in eis_exams.\n";
            }
            else {
                $table->text('instructions')->nullable()->after('title');
                echo "Added instructions to eis_exams.\n";
            }
        }
    });

    Schema::table('eis_exams', function (Blueprint $table) {
        if (!Schema::hasColumn('eis_exams', 'passing_rate')) {
            if (Schema::hasColumn('eis_exams', 'passing_score')) {
                $table->renameColumn('passing_score', 'passing_rate');
                echo "Renamed passing_score to passing_rate in eis_exams.\n";
            }
            else {
                $table->decimal('passing_rate', 5, 2)->nullable()->after('time_limit');
                echo "Added passing_rate to eis_exams.\n";
            }
        }
    });

    Schema::table('eis_exams', function (Blueprint $table) {
        if (!Schema::hasColumn('eis_exams', 'question_json')) {
            $table->json('question_json')->nullable()->after('passing_rate');
            echo "Added question_json to eis_exams.\n";
        }
        if (!Schema::hasColumn('eis_exams', 'answers_json')) {
            $table->json('answers_json')->nullable()->after('question_json');
            echo "Added answers_json to eis_exams.\n";
        }
        if (!Schema::hasColumn('eis_exams', 'answer_json')) {
            $table->json('answer_json')->nullable()->after('answers_json');
            echo "Added answer_json to eis_exams.\n";
        }
    });

    if (!Schema::hasColumn('eis_exams', 'status') && Schema::hasColumn('eis_exams', 'is_active')) {
        Schema::table('eis_exams', function (Blueprint $table) {
            $table->renameColumn('is_active', 'status');
        });
        DB::statement('UPDATE eis_exams SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END');
        echo "Renamed is_active to status and flipped values in eis_exams.\n";
    }
    elseif (!Schema::hasColumn('eis_exams', 'status')) {
        Schema::table('eis_exams', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->after('time_limit');
        });
        echo "Added status to eis_exams.\n";
    }
}
