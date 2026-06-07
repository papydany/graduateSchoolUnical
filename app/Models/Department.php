<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Department extends Model
{
    //
    protected $connection= 'test';
      public function programme_of_study(): HasMany
    {
        return $this->hasMany( ProgrammeOfStudy::class);
    }
}
