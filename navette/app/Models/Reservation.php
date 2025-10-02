<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'navette_id',
        'passenger_count',
        'contact_phone',
        'special_requests',
        'status',
        'total_price',
        'payment_status',
        'payment_method'
    ];

    protected $casts = [
        'passenger_count' => 'integer',
        'total_price' => 'decimal:2',
    ];

    // Reservation.php
    public function navette()
    {
        return $this->belongsTo(Navette::class, 'navette_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
