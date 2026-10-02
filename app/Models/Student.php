<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $fillable = [
        'uuid',
        'faculty_id',
        'department_id',
        'programme_id',
        'programme_of_study_id',
        'specialization_id',
        'entry_session',
        'surname',
        'firstname',
        'othername',
        'registration_number',
        'matriculation_number',
        'email',
        'phone',
        'gender',
        'country',
        'state',
        'lga',
        'image_url',
        'matrital_status',
        'password',
        'uploaded_by',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * The students table has no remember_token column.
     */
    protected $rememberTokenName = false;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
