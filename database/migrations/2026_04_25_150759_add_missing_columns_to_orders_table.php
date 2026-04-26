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
            $table->integer('quantity')->after('total_amount');
            $table->decimal('price_per_unit', 10, 2)->after('quantity');
            $table->string('name_of_products')->after('price_per_unit');
            $table->string('transportation_type')->after('name_of_products');
            $table->string('delivery_destination')->after('transportation_type');
            $table->string('cargo_size')->after('delivery_destination');
            $table->string('type_of_goods')->after('cargo_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'price_per_unit', 'name_of_products', 'transportation_type', 'delivery_destination', 'cargo_size', 'type_of_goods']);
        });
    }
};
