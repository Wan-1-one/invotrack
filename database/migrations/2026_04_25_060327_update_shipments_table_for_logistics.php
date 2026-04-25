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
        // Add new logistics timestamps
        Schema::table('shipments', function (Blueprint $table) {
            $table->timestamp('pickup_started_at')->nullable()->after('status');
            $table->timestamp('picked_up_at')->nullable()->after('pickup_started_at');
            $table->timestamp('arrived_at_port_at')->nullable()->after('picked_up_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove new columns
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['pickup_started_at', 'picked_up_at', 'arrived_at_port_at']);
        });
    }
};
