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
        Schema::table('payments', function (Blueprint $table) {
            // Add payment proof file path
            $table->string('payment_proof_file_path')->nullable()->after('transaction_reference');
            
            // Add customer identifier for filtering
            $table->string('customer_email')->nullable()->after('payment_proof_file_path');
            $table->string('customer_phone')->nullable()->after('customer_email');
            
            // Add indexes
            $table->index('customer_email');
            $table->index('customer_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_proof_file_path');
            $table->dropColumn('customer_email');
            $table->dropColumn('customer_phone');
            $table->dropIndex(['customer_email']);
            $table->dropIndex(['customer_phone']);
        });
    }
};
