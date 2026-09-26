<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $connection = 'test';

    protected $table = 'faculties';

    protected $fillable = ['faculty_name', 'status', 'programme_id', 'sandwish'];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'faculty_id');
    }
}
