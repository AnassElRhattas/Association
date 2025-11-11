<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'registration_date',
        'profile_photo',
        'birth_certificate',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    
}
