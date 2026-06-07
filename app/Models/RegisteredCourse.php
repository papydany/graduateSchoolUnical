<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisteredCourse extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'uuid', 'course_id', 'programme_of_study_id', 'level_id',
        'code', 'title', 'unit', 'semester', 'session', 'status',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }
}
