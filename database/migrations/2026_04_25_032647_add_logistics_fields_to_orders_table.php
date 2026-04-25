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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            $table->integer('quantity')->nullable()->after('total_amount');
            $table->decimal('price_per_unit', 10, 2)->nullable()->after('quantity');
            $table->string('name_of_products')->nullable()->after('price_per_unit');
            $table->string('transportation_type')->nullable()->after('name_of_products');
            $table->string('delivery_destination')->nullable()->after('transportation_type');
            $table->string('cargo_size')->nullable()->after('delivery_destination');
            $table->string('type_of_goods')->nullable()->after('cargo_size');
            
            // Add foreign key if customer_id is used
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn([
                'customer_id',
                'quantity',
                'price_per_unit',
                'name_of_products',
                'transportation_type',
                'delivery_destination',
                'cargo_size',
                'type_of_goods'
            ]);
        });
    }
};
