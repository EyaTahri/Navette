<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navette extends Model
{
    use HasFactory;

    // Allow mass assignment for these attributes
    protected $fillable = [
        'destination',
        'departure',
        'arrival',
        'departure_datetime',
        'arrival_datetime',
        'vehicle_id',
        'vehicle_type',
        'brand',
        'price_per_person',
        'vehicle_price',
        'brand_price',
        'capacity',
        'description',
        'images',
        'is_special_offer',
        'discount_percentage',
        'creator',
        'accepted',
        'special'
    ];

    // Cast attributes
    protected $casts = [
        'departure_datetime' => 'datetime',
        'arrival_datetime' => 'datetime',
        'images' => 'array',
        'is_special_offer' => 'boolean',
        'accepted' => 'boolean',
    ];

    // Relations
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'navette_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator');
    }

}
