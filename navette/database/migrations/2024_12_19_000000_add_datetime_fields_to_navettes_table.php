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
        Schema::table('navettes', function (Blueprint $table) {
            $table->datetime('departure_datetime')->nullable()->after('arrival');
            $table->datetime('arrival_datetime')->nullable()->after('departure_datetime');
            $table->integer('capacity')->default(4)->after('brand_price');
            $table->text('description')->nullable()->after('capacity');
            $table->json('images')->nullable()->after('description');
            $table->boolean('is_special_offer')->default(false)->after('special');
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('is_special_offer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('navettes', function (Blueprint $table) {
            $table->dropColumn([
                'departure_datetime',
                'arrival_datetime', 
                'capacity',
                'description',
                'images',
                'is_special_offer',
                'discount_percentage'
            ]);
        });
    }
};

