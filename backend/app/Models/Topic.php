<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    protected $table = 'eis_topic';
    protected $fillable = ['title', 'description', 'is_active', 'created_by'];
    //

    /**
     * Get the user who created this material.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function materials()
    {
        return $this->hasMany(Material::class, 'topic_id');
    }
}
