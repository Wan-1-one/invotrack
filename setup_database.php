<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Create tables manually
echo "Creating database tables...\n";

try {
    // Create users table
    DB::statement("
        CREATE TABLE IF NOT EXISTS users (
            id BIGINT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'customer') DEFAULT 'customer',
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "Users table created.\n";

    // Create customer_profiles table
    DB::statement("
        CREATE TABLE IF NOT EXISTS customer_profiles (
            id BIGINT PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNIQUE NOT NULL,
            phone VARCHAR(20) NULL,
            address TEXT NOT NULL,
            city VARCHAR(100) NULL,
            state VARCHAR(100) NULL,
            postal_code VARCHAR(20) NULL,
            country VARCHAR(100) DEFAULT 'Malaysia',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "Customer profiles table created.\n";

    // Create orders table
    DB::statement("
        CREATE TABLE IF NOT EXISTS orders (
            id BIGINT PRIMARY KEY AUTO_INCREMENT,
            customer_id BIGINT NULL,
            order_number VARCHAR(255) UNIQUE NOT NULL,
            customer_name VARCHAR(255) NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            customer_phone VARCHAR(20) NULL,
            customer_address TEXT NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            quantity INT NULL,
            price_per_unit DECIMAL(10,2) NULL,
            name_of_products VARCHAR(255) NULL,
            transportation_type VARCHAR(255) NULL,
            delivery_destination VARCHAR(255) NULL,
            cargo_size VARCHAR(255) NULL,
            type_of_goods VARCHAR(255) NULL,
            notes TEXT NULL,
            status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "Orders table created.\n";

    // Create invoices table
    DB::statement("
        CREATE TABLE IF NOT EXISTS invoices (
            id BIGINT PRIMARY KEY AUTO_INCREMENT,
            invoice_number VARCHAR(255) UNIQUE NOT NULL,
            order_id BIGINT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            status ENUM('draft', 'issued', 'paid', 'closed') DEFAULT 'draft',
            issue_date DATE NULL,
            due_date DATE NULL,
            paid_date DATE NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        )
    ");
    echo "Invoices table created.\n";

    // Create payments table
    DB::statement("
        CREATE TABLE IF NOT EXISTS payments (
            id BIGINT PRIMARY KEY AUTO_INCREMENT,
            invoice_id BIGINT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            payment_date DATE NOT NULL,
            status ENUM('pending', 'verified') DEFAULT 'pending',
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
        )
    ");
    echo "Payments table created.\n";

    // Create shipments table
    DB::statement("
        CREATE TABLE IF NOT EXISTS shipments (
            id BIGINT PRIMARY KEY AUTO_INCREMENT,
            invoice_id BIGINT UNIQUE NOT NULL,
            tracking_number VARCHAR(255) UNIQUE NOT NULL,
            courier_name VARCHAR(255) NOT NULL,
            shipping_address TEXT NOT NULL,
            status ENUM('booking_confirmed', 'lorry_assigned', 'en_route_to_pickup', 'cargo_picked_up', 'in_transit_to_port', 'arrived_at_port') DEFAULT 'booking_confirmed',
            shipped_date DATE NULL,
            delivered_date DATE NULL,
            notes TEXT NULL,
            pod_file_path VARCHAR(255) NULL,
            proof_of_arrival_file_path VARCHAR(255) NULL,
            pickup_started_at TIMESTAMP NULL,
            picked_up_at TIMESTAMP NULL,
            arrived_at_port_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
        )
    ");
    echo "Shipments table created.\n";

    // Create sessions table (for database session driver)
    DB::statement("
        CREATE TABLE IF NOT EXISTS sessions (
            id VARCHAR(255) PRIMARY KEY,
            user_id BIGINT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            payload TEXT NOT NULL,
            last_activity INT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "Sessions table created.\n";

    // Create migrations table (if it doesn't exist)
    DB::statement("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL
        )
    ");
    echo "Migrations table created.\n";

    echo "\nDatabase setup completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
