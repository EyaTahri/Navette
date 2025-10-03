<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add missing columns if they don't exist
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'passenger_count')) {
                $table->integer('passenger_count')->default(1)->after('navette_id');
            }
            if (!Schema::hasColumn('reservations', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('passenger_count');
            }
            if (!Schema::hasColumn('reservations', 'special_requests')) {
                $table->text('special_requests')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('reservations', 'total_price')) {
                $table->decimal('total_price', 8, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('reservations', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('total_price');
            }
            if (!Schema::hasColumn('reservations', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
        });

        // Ensure status is ENUM('pending','confirmed','cancelled') with default 'pending'
        // Use raw SQL to avoid needing doctrine/dbal
        try {
            DB::statement("ALTER TABLE `reservations` MODIFY `status` ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
            // If status column doesn't exist yet, create it
            if (!Schema::hasColumn('reservations', 'status')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->enum('status', ['pending','confirmed','cancelled'])->default('pending')->after('special_requests');
                });
            } else {
                throw $e;
            }
        }
    }

    public function down(): void
    {
        // Best-effort rollback: we won't revert enum definition automatically
        // but we can drop columns that we might have added (optional)
        Schema::table('reservations', function (Blueprint $table) {
            // No drops to avoid data loss on rollback
        });
    }
};
