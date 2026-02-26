<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $table = 'eis_exam_results';
    protected $primaryKey = 'result_id';

    protected $fillable = [
        'user_id',
        'fk_exam_id',
        'score',
        'total_points',
        'employee_answer',
        'taken_at',
        'finish_at',
        'status',
    ];

    protected $casts = [
        'answers_json' => 'array',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];
}
