<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insérer des données de test pour les navettes
        $testNavettes = [
            [
                'creator' => 1, // Admin user
                'destination' => 'Paris',
                'departure' => 'Lyon',
                'arrival' => 'Paris',
                'departure_datetime' => now()->addDays(1)->setTime(8, 0),
                'arrival_datetime' => now()->addDays(1)->setTime(12, 0),
                'vehicle_type' => 'Voiture',
                'brand' => 'Peugeot 308',
                'price_per_person' => 25.00,
                'vehicle_price' => 15.00,
                'brand_price' => 5.00,
                'capacity' => 4,
                'description' => 'Trajet confortable avec chauffeur expérimenté',
                'is_special_offer' => false,
                'discount_percentage' => null,
                'accepted' => true,
                'special' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'creator' => 1,
                'destination' => 'Marseille',
                'departure' => 'Nice',
                'arrival' => 'Marseille',
                'departure_datetime' => now()->addDays(2)->setTime(14, 30),
                'arrival_datetime' => now()->addDays(2)->setTime(17, 30),
                'vehicle_type' => 'Minibus',
                'brand' => 'Mercedes Sprinter',
                'price_per_person' => 35.00,
                'vehicle_price' => 20.00,
                'brand_price' => 8.00,
                'capacity' => 8,
                'description' => 'Minibus spacieux avec climatisation',
                'is_special_offer' => true,
                'discount_percentage' => 15.00,
                'accepted' => true,
                'special' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'creator' => 1,
                'destination' => 'Toulouse',
                'departure' => 'Bordeaux',
                'arrival' => 'Toulouse',
                'departure_datetime' => now()->addDays(3)->setTime(9, 0),
                'arrival_datetime' => now()->addDays(3)->setTime(11, 30),
                'vehicle_type' => 'Bus',
                'brand' => 'Iveco Bus',
                'price_per_person' => 20.00,
                'vehicle_price' => 10.00,
                'brand_price' => 3.00,
                'capacity' => 20,
                'description' => 'Bus moderne avec WiFi gratuit',
                'is_special_offer' => false,
                'discount_percentage' => null,
                'accepted' => true,
                'special' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'creator' => 1,
                'destination' => 'Lille',
                'departure' => 'Amiens',
                'arrival' => 'Lille',
                'departure_datetime' => now()->addDays(4)->setTime(16, 0),
                'arrival_datetime' => now()->addDays(4)->setTime(18, 0),
                'vehicle_type' => 'Van',
                'brand' => 'Ford Transit',
                'price_per_person' => 18.00,
                'vehicle_price' => 12.00,
                'brand_price' => 4.00,
                'capacity' => 6,
                'description' => 'Van confortable pour petits groupes',
                'is_special_offer' => true,
                'discount_percentage' => 20.00,
                'accepted' => true,
                'special' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($testNavettes as $navette) {
            DB::table('navettes')->insert($navette);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les données de test
        DB::table('navettes')->whereIn('destination', ['Paris', 'Marseille', 'Toulouse', 'Lille'])->delete();
    }
};







