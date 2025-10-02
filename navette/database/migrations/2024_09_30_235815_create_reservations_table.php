<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key for the user
            $table->unsignedBigInteger('navette_id'); // Foreign key for the navette
            $table->integer('passenger_count')->default(1); // Number of passengers
            $table->string('contact_phone')->nullable(); // Contact phone number
            $table->text('special_requests')->nullable(); // Special requests or notes
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending'); // Reservation status
            $table->decimal('total_price', 8, 2); // Add the total_price field
            $table->string('payment_status')->default('pending'); // Payment status
            $table->string('payment_method')->nullable(); // Payment method used
            
            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('navette_id')->references('id')->on('navettes')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
}
