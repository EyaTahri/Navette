<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'brand',
        'model',
        'vehicle_type',
        'year',
        'capacity',
        'license_plate',
        'color',
        'fuel_type',
        'transmission',
        'features',
        'images',
        'status',
        'is_active',
        'maintenance_date',
        'insurance_expiry',
        'description',
        'daily_rate',
        'hourly_rate',
        'km_rate'
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'maintenance_date' => 'date',
        'insurance_expiry' => 'date',
        'daily_rate' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'km_rate' => 'decimal:2',
    ];

    /**
     * Relation avec l'agence propriétaire
     */
    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_id');
    }

    /**
     * Relation avec les navettes utilisant ce véhicule
     */
    public function navettes()
    {
        return $this->hasMany(Navette::class, 'vehicle_id');
    }

    /**
     * Relation avec les réservations
     */
    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Navette::class, 'vehicle_id', 'navette_id');
    }

    /**
     * Scope pour les véhicules actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les véhicules disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Vérifier si le véhicule est disponible
     */
    public function isAvailable()
    {
        return $this->is_active && $this->status === 'available';
    }

    /**
     * Obtenir l'image principale
     */
    public function getMainImageAttribute()
    {
        $images = $this->images;
        return $images && count($images) > 0 ? $images[0] : null;
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'available' => 'Disponible',
            'in_use' => 'En cours d\'utilisation',
            'maintenance' => 'En maintenance',
            'out_of_service' => 'Hors service',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir le type de carburant formaté
     */
    public function getFuelTypeLabelAttribute()
    {
        return match($this->fuel_type) {
            'gasoline' => 'Essence',
            'diesel' => 'Diesel',
            'electric' => 'Électrique',
            'hybrid' => 'Hybride',
            'lpg' => 'GPL',
            default => 'Non spécifié'
        };
    }

    /**
     * Obtenir la transmission formatée
     */
    public function getTransmissionLabelAttribute()
    {
        return match($this->transmission) {
            'manual' => 'Manuelle',
            'automatic' => 'Automatique',
            'semi_automatic' => 'Semi-automatique',
            default => 'Non spécifié'
        };
    }
}






