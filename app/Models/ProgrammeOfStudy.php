<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class ProgrammeOfStudy extends Model
{
    use SoftDeletes;
 use HasUuids;
    protected $fillable = [
        'name',
        'faculty_id',
        'department_id',
        'programme_id',
        'status',
    ];

    protected $casts = [
        'duration' => 'integer',
    ];

  /*  public const DEGREE_TYPES = [
        'M.Sc'  => 'Master of Science (M.Sc)',
        'M.A'   => 'Master of Arts (M.A)',
        'M.Eng' => 'Master of Engineering (M.Eng)',
        'M.Ed'  => 'Master of Education (M.Ed)',
        'MBA'   => 'Master of Business Administration (MBA)',
        'Ph.D'  => 'Doctor of Philosophy (Ph.D)',
        'PGD'   => 'Postgraduate Diploma (PGD)',
    ];*/

   /* public function isActive(): bool
    {
        return $this->status === 'active';
    }*/

    
    
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
    public function uniqueIds(): array
    {
        return ['uuid'];
    }
    public function durations(): HasMany
    {
        return $this->hasMany(Duration::class, 'programme_of_study');
    }
}
