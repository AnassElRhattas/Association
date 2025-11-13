<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorizationProgress extends Model
{
    use HasFactory;

    protected $table = 'memorization_progress';

    protected $fillable = [
        'student_id',
        'sura_name',
        'verse_start',
        'verse_end',
        'date',
        'note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}