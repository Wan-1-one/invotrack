# INVOTRACK - Invoice & Shipment Management System

A full-stack Laravel web application for managing invoice generation, payment processing, shipment tracking, and transaction closure.

## Features

- **Order Management**: Create and manage customer orders
- **Invoice Generation**: Auto-generate invoices when orders are created
- **Payment Processing**: Record and verify payments
- **Shipment Tracking**: Create shipments with tracking numbers
- **Proof of Delivery**: Upload POD files for completed shipments
- **Workflow Management**: Complete workflow from order to delivery
- **Authentication**: Secure admin login system
- **Clean Business UI**: Professional interface using Tailwind CSS

## System Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx)

## Installation

### 1. Setup Database

Create a MySQL database named `invotrack`:

```sql
CREATE DATABASE invotrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Configure Environment

1. Copy the environment file:
```bash
cp .env.example .env
```

2. Update database credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invotrack
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

3. Generate application key:
```bash
php artisan key:generate
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Run Database Migrations

```bash
php artisan migrate
```

### 5. Seed Database (Create Admin User)

```bash
php artisan db:seed
```

### 6. Setup Storage Link

```bash
php artisan storage:link
```

### 7. Start Development Server

```bash
php artisan serve
```

## Default Login Credentials

- **Email**: 123
- **Password**: 123

## Workflow Process

The system follows this business workflow:

1. **Order Placement & Invoice Generation**
Customer places an order or requests a service in the system. The system automatically generates a draft invoice based on the order details to ensure no manual entry errors.
Purpose: Start transaction and create initial financial record.

2. **Invoice Issuance & Verification**
The draft invoice is sent for customer review. The customer verifies pricing, quantity, and details. Any errors are corrected before approval.
Purpose: Ensure invoice accuracy before payment stage.

3. **Payment Processing**
Once the invoice is confirmed, the customer makes payment. The system or finance team verifies the payment before approval.
Purpose: Secure and confirm financial transaction before delivery.

4. **Delivery Arrangement & Shipment**
After payment is verified, the system triggers shipment preparation. Goods are packed and dispatched using assigned delivery methods.
Purpose: Execute logistics after financial confirmation.

5. **Shipment Tracking & Proof of Delivery (POD)**
The shipment is tracked in real time until delivery is completed. Once received, Proof of Delivery (POD) is uploaded into the system.
Purpose: Confirm successful delivery of goods.

6. **Transaction Closure**
The system verifies that payment is completed and POD is uploaded. Once all conditions are met, the transaction is officially closed.
Purpose: Finalize and archive the complete transaction record.

## Project Structure

```
invotrack-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
├── routes/
└── public/
```

## Database Schema

### Tables
- **users**: Authentication and user management
- **orders**: Customer orders and details
- **invoices**: Invoice information and status
- **payments**: Payment records and verification
- **shipments**: Shipment tracking and delivery info

### Relationships
- Order → Invoice (1:1)
- Invoice → Payments (1:N)
- Invoice → Shipment (1:1)

## Usage

### Creating Orders
1. Navigate to Orders → Create Order
2. Fill in customer information and order details
3. Invoice is automatically generated

### Managing Invoices
1. View all invoices from the Invoices page
2. Issue invoices to enable payment processing
3. Track workflow progress

### Recording Payments
1. From invoice details, click "Record Payment"
2. Enter payment details and amount
3. Verify payment to update invoice status

### Creating Shipments
1. After payment verification, create shipment
2. Enter courier and shipping details
3. Tracking number is auto-generated

### Managing Shipments
1. Update shipment status (pending → shipped → delivered)
2. Upload proof of delivery files
3. Transaction automatically closes when delivered

## Security Features

- Authentication middleware protection
- CSRF token validation
- Input validation and sanitization
- Secure password hashing

## Technologies Used

- **Backend**: Laravel 10
- **Database**: MySQL
- **Frontend**: Blade Templates
- **CSS**: Tailwind CSS
- **Authentication**: Laravel Auth

## License

This project is for demonstration purposes.

## Support

For issues and questions, please refer to the documentation or contact the development team.
