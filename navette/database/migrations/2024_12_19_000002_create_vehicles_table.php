<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id'); // Foreign key vers users (agence)
            $table->string('brand'); // Marque (ex: Peugeot, Mercedes)
            $table->string('model'); // Modèle (ex: 308, Sprinter)
            $table->string('vehicle_type'); // Type (ex: Voiture, Minibus, Bus)
            $table->year('year'); // Année de fabrication
            $table->integer('capacity'); // Capacité en passagers
            $table->string('license_plate')->unique(); // Plaque d'immatriculation
            $table->string('color')->nullable(); // Couleur
            $table->enum('fuel_type', ['gasoline', 'diesel', 'electric', 'hybrid', 'lpg'])->default('gasoline');
            $table->enum('transmission', ['manual', 'automatic', 'semi_automatic'])->default('manual');
            $table->json('features')->nullable(); // Équipements (climatisation, GPS, etc.)
            $table->json('images')->nullable(); // Photos du véhicule
            $table->enum('status', ['available', 'in_use', 'maintenance', 'out_of_service'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->date('maintenance_date')->nullable(); // Dernière maintenance
            $table->date('insurance_expiry')->nullable(); // Expiration assurance
            $table->text('description')->nullable(); // Description détaillée
            $table->decimal('daily_rate', 8, 2)->nullable(); // Tarif journalier
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Tarif horaire
            $table->decimal('km_rate', 8, 2)->nullable(); // Tarif au kilomètre
            
            // Foreign key constraint
            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};






