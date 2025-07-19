# MS Glow Store - E-Commerce Platform

<div align="center">
  <img src="docs/erd_diagram.png" alt="MS Glow Store Logo" width="200"/>
  
  [![PHP Version](https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
  [![Apache](https://img.shields.io/badge/Apache-2.4+-D22128?style=flat-square&logo=apache&logoColor=white)](https://apache.org)
  [![Bootstrap](https://img.shields.io/badge/Bootstrap-5.0+-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
  [![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)
</div>

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Database Schema](#database-schema)
- [Contributing](#contributing)
- [License](#license)
- [Support](#support)

---

## 🎯 Overview

**MS Glow Store** adalah platform e-commerce yang dikembangkan khusus untuk menjual produk kecantikan MS Glow. Aplikasi ini menyediakan pengalaman berbelanja online yang lengkap dengan fitur manajemen produk, sistem checkout yang user-friendly, dan panel admin yang komprehensif.

### Key Highlights
- 🛍️ **Modern E-commerce Experience** - Interface yang intuitive dan responsive
- 🔐 **Secure Authentication** - Sistem login yang aman dengan role-based access
- 📦 **Complete Order Management** - Dari checkout hingga delivery tracking
- 💳 **Multiple Payment Options** - COD, Transfer Bank, dan E-Wallet
- 📊 **Admin Dashboard** - Komprehensif untuk manajemen bisnis
- 🎨 **Responsive Design** - Optimized untuk desktop dan mobile

---

## ✨ Features

### For Customers
- [x] User registration and authentication
- [x] Product catalog with search and filter
- [x] Shopping cart management
- [x] Multi-step checkout process
- [x] Multiple shipping options
- [x] Order history and tracking
- [x] User profile management
- [x] Responsive design

### For Administrators
- [x] Comprehensive admin dashboard
- [x] Product management (CRUD operations)
- [x] Category management
- [x] Order processing and status updates
- [x] Payment method configuration
- [x] User management
- [x] Sales analytics and reports

### Technical Features
- [x] Session-based authentication
- [x] SQL injection protection
- [x] File upload validation
- [x] Image optimization
- [x] Database relationship integrity
- [x] Error handling and logging

---

## 🛠️ Technology Stack

### Backend
- **Language**: PHP 7.4+
- **Database**: MySQL 8.0+
- **Web Server**: Apache 2.4+
- **Architecture**: MVC Pattern

### Frontend
- **Framework**: Bootstrap 5.0+
- **JavaScript**: Vanilla JS + jQuery
- **CSS**: Custom CSS + Bootstrap
- **Icons**: Font Awesome

### Development Tools
- **Version Control**: Git
- **Development Environment**: XAMPP/MAMP
- **Database Management**: phpMyAdmin
- **Code Editor**: VS Code (recommended)

---

## 🚀 Installation

### Prerequisites
Pastikan sistem Anda memiliki:
- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Apache Web Server
- Git (opsional)

### Quick Start
1. **Clone repository**
   ```bash
   git clone https://github.com/your-username/MSglowstore.git
   cd MSglowstore
   ```

2. **Setup database**
   ```sql
   CREATE DATABASE msglow_store;
   ```

3. **Import database structure**
   ```bash
   mysql -u root -p msglow_store < msglow/database_update.sql
   ```

4. **Configure database connection**
   ```php
   // Edit msglow/config/database.php
   $host = "localhost";
   $username = "root";
   $password = "";
   $database = "msglow_store";
   ```

5. **Set file permissions**
   ```bash
   chmod -R 755 msglow/uploads/
   ```

6. **Access application**
   ```
   http://localhost/MSglowstore/msglow/
   ```

📚 **Detailed Installation Guide**: [docs/INSTALLATION.md](docs/INSTALLATION.md)

---

## 💡 Usage

### Customer Workflow
1. **Browse Products** → Browse catalog atau search products
2. **Add to Cart** → Select products and add to shopping cart
3. **Checkout** → Complete order with shipping and payment details
4. **Order Confirmation** → Receive order confirmation
5. **Track Order** → Monitor order status and delivery

### Admin Workflow
1. **Login to Admin Panel** → Access admin dashboard
2. **Manage Products** → Add, edit, or remove products
3. **Process Orders** → Update order status and manage fulfillment
4. **Monitor Analytics** → View sales reports and statistics

📖 **Complete User Guide**: [docs/USAGE.md](docs/USAGE.md)

---

## 🗄️ Database Schema

### Core Tables
- **users** - User accounts and authentication
- **products** - Product catalog and inventory
- **categories** - Product categorization
- **orders** - Order management
- **order_items** - Order line items
- **payment_methods** - Payment options

### Entity Relationships
```mermaid
erDiagram
    users ||--o{ orders : places
    orders ||--o{ order_items : contains
    products ||--o{ order_items : includes
    categories ||--o{ products : categorizes
    payment_methods ||--o{ orders : processes
```

📊 **Database Documentation**: [docs/DATABASE.md](docs/DATABASE.md)

---

## 🚢 Deployment

### Production Deployment
1. **Server Setup** - Configure web server and database
2. **Environment Configuration** - Set production settings
3. **Database Migration** - Deploy schema and data
4. **Security Hardening** - Implement security best practices
5. **Performance Optimization** - Configure caching and optimization

🔧 **Deployment Guide**: [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md)

---

## 📁 Project Structure

```
MSglowstore/
├── docs/                      # Documentation
│   ├── DATABASE.md           # Database schema and setup
│   ├── DEPLOYMENT.md         # Deployment guide
│   ├── INSTALLATION.md       # Installation instructions
│   ├── USAGE.md             # User guide
│   └── erd_diagram.png      # Entity relationship diagram
├── msglow/                   # Main application
│   ├── admin/               # Admin panel
│   ├── categories/          # Category management
│   ├── config/              # Configuration files
│   ├── orders/              # Order management
│   ├── payment_methods/     # Payment method management
│   ├── products/            # Product management
│   ├── uploads/             # File uploads
│   ├── dashboard.php        # Admin dashboard
│   ├── login.php           # User authentication
│   ├── register.php        # User registration
│   └── index.php           # Main homepage
└── README.md               # This file
```

---

# 🛍️ MS Glow Store - Database Design

Dokumentasi ini menjelaskan struktur database relasional untuk sistem aplikasi **MS Glow Store**, yang berfungsi sebagai backend dari platform penjualan produk skincare.

---

## 🧱 Database: `msglowstore`

Struktur database ini terdiri dari **10 tabel** utama yang saling terhubung melalui relasi Foreign Key. Tabel-tabel ini mencakup data user, produk, pelanggan, pesanan, pembayaran, dan aktivitas sistem.

---

## 🗃️ Struktur Tabel

### 1. `roles`
Menyimpan daftar peran pengguna.

| Kolom     | Tipe Data     | Keterangan          |
|-----------|---------------|---------------------|
| id        | INT           | Primary Key         |
| role_name | VARCHAR(50)   | Nama peran (admin, user, dll) |

---

### 2. `users`
Menyimpan informasi akun pengguna/admin sistem.

| Kolom      | Tipe Data     | Keterangan              |
|------------|---------------|-------------------------|
| id         | INT           | Primary Key             |
| name       | VARCHAR(100)  | Nama pengguna           |
| email      | VARCHAR(100)  | Unik, digunakan login   |
| password   | VARCHAR(255)  | Password terenkripsi    |
| role_id    | INT           | FK → `roles.id`         |
| created_at | TIMESTAMP     | Tanggal dibuat akun     |

---

### 3. `customers`
Informasi pelanggan yang melakukan pembelian.

| Kolom   | Tipe Data     | Keterangan     |
|---------|---------------|----------------|
| id      | INT           | Primary Key    |
| name    | VARCHAR(100)  | Nama pelanggan |
| email   | VARCHAR(100)  | Email          |
| phone   | VARCHAR(20)   | Nomor telepon  |
| address | TEXT          | Alamat lengkap |

---

### 4. `categories`
Kategori produk (serum, cream, dll).

| Kolom         | Tipe Data     | Keterangan       |
|---------------|---------------|------------------|
| id            | INT           | Primary Key      |
| category_name | VARCHAR(100)  | Nama kategori    |
| description   | TEXT          | Deskripsi opsional |

---

### 5. `products`
Informasi detail produk skincare.

| Kolom        | Tipe Data     | Keterangan                |
|--------------|---------------|---------------------------|
| id           | INT           | Primary Key               |
| name         | VARCHAR(100)  | Nama produk               |
| description  | TEXT          | Deskripsi produk          |
| price        | DECIMAL       | Harga                     |
| stock        | INT           | Jumlah stok               |
| category_id  | INT           | FK → `categories.id`      |
| image        | VARCHAR(255)  | Path gambar produk        |

---

### 6. `payment_methods`
Pilihan metode pembayaran.

| Kolom        | Tipe Data     | Keterangan           |
|--------------|---------------|----------------------|
| id           | INT           | Primary Key          |
| method_name  | VARCHAR(50)   | Nama metode (COD, Transfer) |
| description  | TEXT          | Deskripsi opsional   |

---

### 7. `orders`
Data pemesanan yang dilakukan oleh pelanggan.

| Kolom             | Tipe Data     | Keterangan                        |
|-------------------|---------------|-----------------------------------|
| id                | INT           | Primary Key                       |
| customer_id       | INT           | FK → `customers.id`               |
| order_date        | DATETIME      | Tanggal transaksi                 |
| total             | DECIMAL       | Total harga pesanan               |
| status            | VARCHAR(50)   | Status pesanan                    |
| shipping_address  | TEXT          | Alamat pengiriman                 |
| payment_method_id | INT           | FK → `payment_methods.id`         |

---

### 8. `order_items`
Detail produk yang termasuk dalam pesanan.

| Kolom      | Tipe Data     | Keterangan                  |
|------------|---------------|-----------------------------|
| id         | INT           | Primary Key                 |
| order_id   | INT           | FK → `orders.id`            |
| product_id | INT           | FK → `products.id`          |
| quantity   | INT           | Jumlah item                 |
| price      | DECIMAL       | Harga per item saat beli    |

---

### 9. `reviews`
Ulasan pelanggan terhadap produk.

| Kolom       | Tipe Data     | Keterangan                   |
|-------------|---------------|------------------------------|
| id          | INT           | Primary Key                  |
| customer_id | INT           | FK → `customers.id`          |
| product_id  | INT           | FK → `products.id`           |
| rating      | INT           | Skala penilaian              |
| comment     | TEXT          | Isi ulasan                   |
| created_at  | TIMESTAMP     | Tanggal ulasan               |

---

### 10. `activity_log`
Log aktivitas pengguna di dalam sistem (admin log, dsb).

| Kolom      | Tipe Data     | Keterangan               |
|------------|---------------|--------------------------|
| id         | INT           | Primary Key              |
| user_id    | INT           | FK → `users.id`          |
| activity   | TEXT          | Aktivitas yang dilakukan |
| created_at | TIMESTAMP     | Waktu aktivitas          |

---

## 🔗 Relasi Antar Tabel (Foreign Keys)

```plaintext
users.role_id → roles.id
products.category_id → categories.id
orders.customer_id → customers.id
orders.payment_method_id → payment_methods.id
order_items.order_id → orders.id
order_items.product_id → products.id
reviews.customer_id → customers.id
reviews.product_id → products.id
activity_log.user_id → users.id


## 🔌 API Documentation

### Available Endpoints
- `GET /api/products` - Retrieve product list
- `POST /api/orders` - Create new order
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}` - Update order status

### Authentication
- Session-based authentication
- Role-based access control (admin/user)
- CSRF protection for forms

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create feature branch** (`git checkout -b feature/AmazingFeature`)
3. **Commit changes** (`git commit -m 'Add some AmazingFeature'`)
4. **Push to branch** (`git push origin feature/AmazingFeature`)
5. **Open Pull Request**

### Development Guidelines
- Follow PSR-4 coding standards
- Write meaningful commit messages
- Test your changes thoroughly
- Update documentation when needed

---

## 📊 Project Statistics

- **Total Files**: 50+ PHP files
- **Database Tables**: 6 core tables
- **Features**: 15+ major features
- **Supported Browsers**: Chrome, Firefox, Safari, Edge
- **Mobile Responsive**: ✅ Yes

---

## 🐛 Bug Reports & Feature Requests

Found a bug or have a feature request? Please create an issue:

1. **Check existing issues** to avoid duplicates
2. **Use issue templates** for consistency
3. **Provide detailed information** about the bug/feature
4. **Include screenshots** if applicable

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙋‍♂️ Support

### Documentation
- 📖 [Installation Guide](docs/INSTALLATION.md)
- 📚 [User Guide](docs/USAGE.md)
- 🗄️ [Database Documentation](docs/DATABASE.md)
- 🚢 [Deployment Guide](docs/DEPLOYMENT.md)

### Contact
- **Email**: support@msglow.com
- **Phone**: +62-XXX-XXXX-XXXX
- **Hours**: 08:00 - 17:00 WIB (Monday - Friday)

### Community
- **GitHub Issues**: For bug reports and feature requests
- **Discussions**: For general questions and community support

---

## 🏆 Acknowledgments

- **MS Glow** - For the amazing beauty products
- **Bootstrap Team** - For the excellent CSS framework
- **PHP Community** - For the robust programming language
- **MySQL Team** - For the reliable database system

---

<div align="center">
  <p>Made with ❤️ by the MS Glow Store Team</p>
  <p>© 2025 MS Glow Store. All rights reserved.</p>
</div>

---

*Last updated: July 17, 2025*
*Version: 1.0.0*
