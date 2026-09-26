<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    protected $table = 'programmes';

    protected $fillable = ['name'];

    public function programmeOfStudies(): HasMany
    {
        return $this->hasMany(ProgrammeOfStudy::class, 'programme_id');
    }
}
