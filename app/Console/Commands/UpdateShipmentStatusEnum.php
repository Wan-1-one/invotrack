<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateShipmentStatusEnum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-shipment-status-enum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update shipment status enum to logistics-based values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Step 1: Convert enum to VARCHAR to allow any value
            $this->info('Converting status column to VARCHAR...');
            DB::statement("ALTER TABLE shipments MODIFY COLUMN status VARCHAR(50) DEFAULT 'booking_confirmed'");
            
            // Step 2: Update existing records to use new status values
            $this->info('Migrating existing shipment statuses...');
            
            DB::table('shipments')->where('status', 'pending')->update(['status' => 'booking_confirmed']);
            DB::table('shipments')->where('status', 'shipped')->update(['status' => 'in_transit_to_port']);
            DB::table('shipments')->where('status', 'delivered')->update(['status' => 'arrived_at_port']);
            
            $this->info('Existing statuses migrated successfully.');
            
            // Step 3: Convert back to ENUM with new values
            $this->info('Converting status column back to ENUM with new values...');
            DB::statement("ALTER TABLE shipments MODIFY COLUMN status ENUM('booking_confirmed', 'lorry_assigned', 'en_route_to_pickup', 'cargo_picked_up', 'in_transit_to_port', 'arrived_at_port') DEFAULT 'booking_confirmed'");
            
            $this->info('Successfully updated shipment status enum to logistics-based values.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error updating shipment status enum: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
