<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    protected $table = 'eis_exams';

    protected $appends = ['is_active'];

    protected $fillable = [
        'fk_topic_id',
        'title',
        'instructions',
        'passing_rate',
        'question_json',
        'answers_json',
        'time_limit',
        'status', // 0 active, 1 inactive
    ];

    protected $casts = [
        'fk_topic_id'   => 'integer',
        'time_limit'    => 'integer',
        'passing_rate'  => 'decimal:2',
        'status'        => 'integer',
        'question_json' => 'array',
        'answers_json'  => 'array',
    ];

    // Convenience accessor: treat status as boolean
    public function getIsActiveAttribute(): bool
    {
        return (int) $this->status === 0;
    }

    public function topic(): BelongsTo
    {
        // IMPORTANT: your FK references eis_topic.id
        return $this->belongsTo(\App\Models\Topic::class, 'fk_topic_id');
        // If your topic model name is different, change Topic::class accordingly.
    }
}
