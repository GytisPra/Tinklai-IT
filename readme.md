# Computer Assembly Management System

A comprehensive web-based platform for managing custom computer assembly, parts inventory, and order processing. Built with PHP and MySQL, this system allows technicians to create device templates, users to assemble custom computers, and administrators to manage the entire workflow.

## Features

### User Management

- **Role-Based Access Control**: Three user roles with distinct permissions
  - **Admin**: Full system access, order management and oversight
  - **Technician**: Device creation and inventory management
  - **User**: Custom computer assembly and order placement

### Device Management

- Create predefined device templates (e.g., Gaming PC, Workstation, Multimedia PC)
- Define device components and base cost
- Edit and manage device specifications
- Track devices by creation date and creator

### Parts Inventory

- Categorized parts management (CPU, Motherboard, RAM, Graphics Card, Storage, Monitor, Cooling, OS)
- Real-time inventory tracking
- Part pricing and availability management
- Multiple options per component category

### Custom Assembly

- Users can assemble computers from predefined device templates
- Select specific components for each part category
- Real-time cost calculation based on selected parts
- Save and manage multiple assemblies
- Edit saved assemblies anytime

### Order Management

- Place orders for custom assemblies
- Track order status (In Progress, Received, Accepted, Cancelled)
- Admin oversight of all orders
- User order history tracking

## Requirements

- **PHP**: 8.0 or higher
- **MySQL/MariaDB**: 5.7 or higher
- **Web Server**: Apache with mod_rewrite enabled
- **Browser**: Modern browser with JavaScript support

## 🚀 Installation

### 1. Database Setup

```bash
# Import the SQL database
mysql -u root < it-project.sql
```

### 2. Configuration

Update `config/config.php` with your database credentials:

```php
<?php
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'it-projektas';
```

### 3. Server Setup

Place the project in your web root directory (typically `htdocs` for XAMPP):

```bash
cp -r Tinklai-IT /path/to/htdocs/
```

### 4. Access Application

Navigate to: `http://localhost/Tinklai-IT/`

## 📁 Project Structure

```
Tinklai-IT/
├── app/
│   ├── controllers/          # Business logic handlers
│   │   ├── DashboardController.php
│   │   ├── DeviceController.php
│   │   ├── LoginController.php
│   │   ├── LogoutController.php
│   │   ├── OrderController.php
│   │   ├── PartController.php
│   │   └── RegisterController.php
│   ├── models/               # Database models
│   │   ├── Device.php
│   │   ├── Order.php
│   │   ├── Part.php
│   │   └── User.php
│   ├── utils/                # Utility functions
│   │   └── utils.php
│   └── views/                # HTML templates
│       ├── assemble-device.php
│       ├── create-part.php
│       ├── create-device.php
│       ├── dashboard.php
│       ├── device.php
│       ├── devices_table.php
│       ├── header.php
│       ├── login.html
│       ├── my-assemblies.php
│       ├── my-devices.php
│       ├── orders.php
│       └── register.html
├── assets/                   # Static files
│   ├── css/
│   │   └── styles.css
│   ├── icons/
│   ├── images/
│   └── js/                   # Client-side validation & interactions
│       ├── app.js
│       ├── assembleDeviceFormValidation.js
│       ├── assembleDeviceLoadData.js
│       ├── assemblyActions.js
│       ├── deviceActions.js
│       ├── deviceFormValidation.js
│       ├── deviceLoadData.js
│       ├── deviceTableLoad.js
│       ├── loginValidation.js
│       ├── orderActions.js
│       ├── partFormValidation.js
│       └── registrationFormValidation.js
├── config/
│   └── config.php            # Database configuration
├── tests/                    # Test files
├── index.php                 # Main router
├── it-project.sql            # Database schema & seed data
└── readme.md                 # This file
```

## Security Features

- **Password Hashing**: bcrypt password hashing with PHP's `password_hash()`
- **Role-Based Authorization**: Route-level access control based on user roles
- **Session Management**: Secure session handling with role verification
- **Input Validation**: Client-side and server-side form validation
- **Prepared Statements**: SQL injection prevention through prepared statements

## Database Schema

### Core Tables

- **user**: User accounts with roles (Admin, Technician, User)
- **device**: Predefined device templates with component configurations
- **part**: Hardware components with pricing and inventory
- **device_parts**: Mapping between devices and their component options
- **user_assembly**: Custom computer assemblies created by users
- **order**: Customer orders for assemblies
- **part_types**: Component categories
- **order_states**: Order status tracking

## User Workflows

### Technician Workflow

1. Login with technician credentials
2. Navigate to "Create Device" to define new device templates
3. Manage inventory through "Create Part" functionality
4. View technician's created devices under "My Devices"

### User Workflow

1. Register account or login
2. Browse available devices from dashboard
3. Click on a device to customize components
4. Save custom assembly with a name
5. Place order for the assembly
6. Track order status in "My Orders"

### Admin Workflow

1. Login with admin credentials
2. Access dashboard for system overview
3. Review and manage all orders
4. Update order statuses (Received, Accepted, Cancelled)
5. Monitor technician-created devices

## 🛠️ API Routes

The application uses a router-based API with the following endpoints:

### Authentication

- `POST /register/submit` - Register new user
- `POST /login/submit` - User login
- `GET /logout` - User logout

### Devices

- `GET /get-all-devices` - List all available devices
- `GET /get-device` - Get device details
- `GET /create-device` - Show device creation form
- `POST /create-device/submit` - Create new device
- `GET /my-devices` - Technician's devices
- `GET /edit-device` - Show device edit form
- `POST /edit-device/update` - Update device
- `GET /delete-device` - Delete device

### Parts

- `GET /get-parts` - Get parts by type
- `GET /get-all-parts` - List all parts
- `GET /get-part` - Get part details
- `GET /get-part-price` - Get part pricing
- `GET /create-part` - Show part creation form
- `POST /create-part/submit` - Create new part
- `POST /check-parts-availability` - Check inventory

### Assemblies

- `GET /assemble-device` - Show assembly form
- `POST /assemble-device/assemble` - Create assembly
- `GET /my-assemblies` - User's assemblies
- `GET /assembly-edit` - Show assembly edit form
- `POST /assembly-edit/update` - Update assembly
- `GET /assembly-delete` - Delete assembly
- `GET /assembly-view` - View assembly details

### Orders

- `POST /assembly-order` - Place order
- `GET /orders` - View orders
- `POST /update-order-status` - Update order status

## Technologies Used

- **Backend**: PHP 8.0+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Architecture**: MVC Pattern

## License

This project is part of a university coursework for KTU (Kaunas University of Technology).
