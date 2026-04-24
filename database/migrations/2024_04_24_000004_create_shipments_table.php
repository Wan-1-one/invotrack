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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number')->unique();
            $table->enum('status', ['pending', 'shipped', 'delivered'])->default('pending');
            $table->date('shipped_date')->nullable();
            $table->date('delivered_date')->nullable();
            $table->string('courier_name');
            $table->text('shipping_address');
            $table->string('pod_file_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
