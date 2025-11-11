<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyEntry extends Model
{
    protected $fillable = [
        'payer_name',
        'month',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];
}
