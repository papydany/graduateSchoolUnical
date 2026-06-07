<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courses extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'uuid', 'department_id', 'faculty_id', 'programme_id',
        'code', 'title', 'unit', 'semester',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
