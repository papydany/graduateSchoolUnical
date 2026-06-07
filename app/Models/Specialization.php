<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'faculty_id', 'department_id', 'uuid'];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
