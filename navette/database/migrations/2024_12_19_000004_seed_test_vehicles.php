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
        // Insérer des véhicules de test pour l'agence (user ID 1)
        $testVehicles = [
            [
                'agency_id' => 1, // Admin user (agence de test)
                'brand' => 'Peugeot',
                'model' => '308',
                'vehicle_type' => 'Voiture',
                'year' => 2022,
                'capacity' => 4,
                'license_plate' => 'AB-123-CD',
                'color' => 'Blanc',
                'fuel_type' => 'gasoline',
                'transmission' => 'manual',
                'features' => json_encode(['air_conditioning', 'gps', 'bluetooth', 'usb_ports']),
                'images' => json_encode([]),
                'status' => 'available',
                'is_active' => true,
                'maintenance_date' => now()->subMonths(2),
                'insurance_expiry' => now()->addMonths(6),
                'description' => 'Voiture confortable avec climatisation et GPS intégré',
                'daily_rate' => 80.00,
                'hourly_rate' => 15.00,
                'km_rate' => 0.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'agency_id' => 1,
                'brand' => 'Mercedes',
                'model' => 'Sprinter',
                'vehicle_type' => 'Minibus',
                'year' => 2021,
                'capacity' => 8,
                'license_plate' => 'EF-456-GH',
                'color' => 'Noir',
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'features' => json_encode(['air_conditioning', 'gps', 'wifi', 'leather_seats', 'parking_sensors']),
                'images' => json_encode([]),
                'status' => 'available',
                'is_active' => true,
                'maintenance_date' => now()->subMonth(),
                'insurance_expiry' => now()->addMonths(8),
                'description' => 'Minibus spacieux avec WiFi gratuit et sièges en cuir',
                'daily_rate' => 150.00,
                'hourly_rate' => 25.00,
                'km_rate' => 0.80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'agency_id' => 1,
                'brand' => 'Iveco',
                'model' => 'Bus',
                'vehicle_type' => 'Bus',
                'year' => 2020,
                'capacity' => 20,
                'license_plate' => 'IJ-789-KL',
                'color' => 'Bleu',
                'fuel_type' => 'diesel',
                'transmission' => 'manual',
                'features' => json_encode(['air_conditioning', 'wifi', 'usb_ports', 'wheelchair_access']),
                'images' => json_encode([]),
                'status' => 'maintenance',
                'is_active' => true,
                'maintenance_date' => now()->subDays(5),
                'insurance_expiry' => now()->addMonths(4),
                'description' => 'Bus moderne avec accès fauteuil roulant et WiFi gratuit',
                'daily_rate' => 200.00,
                'hourly_rate' => 35.00,
                'km_rate' => 1.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'agency_id' => 1,
                'brand' => 'Ford',
                'model' => 'Transit',
                'vehicle_type' => 'Van',
                'year' => 2023,
                'capacity' => 6,
                'license_plate' => 'MN-012-OP',
                'color' => 'Gris',
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'features' => json_encode(['air_conditioning', 'gps', 'bluetooth', 'cruise_control']),
                'images' => json_encode([]),
                'status' => 'available',
                'is_active' => true,
                'maintenance_date' => now()->subWeeks(2),
                'insurance_expiry' => now()->addMonths(10),
                'description' => 'Van confortable pour petits groupes avec régulateur de vitesse',
                'daily_rate' => 120.00,
                'hourly_rate' => 20.00,
                'km_rate' => 0.60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($testVehicles as $vehicle) {
            DB::table('vehicles')->insert($vehicle);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les véhicules de test
        DB::table('vehicles')->whereIn('license_plate', ['AB-123-CD', 'EF-456-GH', 'IJ-789-KL', 'MN-012-OP'])->delete();
    }
};







