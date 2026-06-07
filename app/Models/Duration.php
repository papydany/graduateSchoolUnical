<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Duration extends Model
{
    use SoftDeletes;

    protected $fillable = ['programme_of_study', 'programme_type_id', 'name'];
}
