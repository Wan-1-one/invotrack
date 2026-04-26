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
        DB::statement("ALTER TABLE shipments MODIFY COLUMN status ENUM('booking_confirmed', 'lorry_assigned', 'en_route_to_pickup', 'cargo_picked_up', 'in_transit_to_port', 'arrived_at_port') DEFAULT 'booking_confirmed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE shipments MODIFY COLUMN status ENUM('pending', 'shipped', 'delivered') DEFAULT 'pending'");
    }
};
