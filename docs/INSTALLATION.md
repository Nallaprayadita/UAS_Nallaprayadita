# Installation Guide

## Overview
MS Glow Store adalah aplikasi e-commerce untuk produk kecantikan yang dibangun dengan PHP dan MySQL. Panduan ini akan membantu Anda menginstal aplikasi di lingkungan development lokal.

## System Requirements

### Minimum Requirements
- **PHP**: 7.4 atau lebih baru
- **MySQL**: 5.7 atau lebih baru (atau MariaDB 10.2+)
- **Web Server**: Apache 2.4+ atau Nginx 1.16+
- **Memory**: 512MB RAM minimum
- **Storage**: 1GB free space minimum

### Recommended Tools
- **XAMPP** (Windows) atau **MAMP** (Mac) untuk development lokal
- **Git** untuk version control
- **Composer** untuk dependency management (opsional)
- **Node.js** untuk asset compilation (opsional)

## Installation Steps

### 1. Download or Clone Repository

#### Option A: Download ZIP
1. Download repository sebagai ZIP file
2. Extract ke direktori web server (contoh: `C:\xampp\htdocs\MSglowstore`)

#### Option B: Clone with Git
```bash
git clone https://github.com/your-repo/MSglowstore.git
cd MSglowstore
```

### 2. Setup Web Server

#### For XAMPP (Windows)
1. Install XAMPP dari [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache dan MySQL dari XAMPP Control Panel
3. Pastikan aplikasi berada di folder `C:\xampp\htdocs\MSglowstore`

#### For MAMP (Mac)
1. Install MAMP dari [https://www.mamp.info/](https://www.mamp.info/)
2. Start Apache dan MySQL dari MAMP interface
3. Pastikan aplikasi berada di folder `/Applications/MAMP/htdocs/MSglowstore`

#### For Linux (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install apache2 php php-mysql mysql-server
sudo systemctl start apache2
sudo systemctl start mysql
```

### 3. Database Setup

#### Create Database
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Login dengan user: `root`, password: (kosong untuk XAMPP)
3. Create database baru dengan nama `msglow_store`

#### Alternative: Using MySQL Command Line
```sql
CREATE DATABASE msglow_store;
USE msglow_store;
```

#### Run Database Migration
1. Import file `msglow/database_update.sql` ke database
2. Atau jalankan query berikut:

```sql
-- Create tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    category_id INT,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    shipping_address TEXT,
    shipping_method VARCHAR(50) DEFAULT 'reguler',
    payment_method VARCHAR(50) DEFAULT 'cod',
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

### 4. Configuration

#### Database Configuration
Edit file `msglow/config/database.php`:

```php
<?php
$host     = "localhost";
$username = "root";
$password = "";  // Kosong untuk XAMPP
$database = "msglow_store";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
```

#### File Permissions
Pastikan folder `msglow/uploads/` memiliki permission yang benar:

**Windows:**
- Klik kanan folder → Properties → Security → Edit
- Berikan Full Control untuk Users

**Linux/Mac:**
```bash
chmod -R 755 msglow/uploads/
```

### 5. Seed Data (Optional)

Untuk testing, tambahkan data sample:

```sql
-- Sample categories
INSERT INTO categories (name, description) VALUES
('Skincare Wajah', 'Produk perawatan wajah MS Glow'),
('Skincare Tubuh', 'Produk perawatan tubuh MS Glow'),
('Makeup', 'Produk makeup dan kosmetik MS Glow');

-- Sample payment methods
INSERT INTO payment_methods (name, description) VALUES
('Cash on Delivery (COD)', 'Pembayaran saat barang diterima'),
('Transfer Bank', 'Transfer ke rekening bank'),
('E-Wallet', 'Pembayaran melalui dompet digital');

-- Sample admin user
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@msglow.com', MD5('admin123'), 'admin');

-- Sample products
INSERT INTO products (name, price, image, description, category_id, stock) VALUES
('MS Glow Facial Wash', 85000, 'produk_wajah.png', 'Pembersih wajah yang lembut dan efektif', 1, 50),
('MS Glow Body Lotion', 120000, 'produk_tubuh.jpg', 'Pelembab tubuh dengan ekstrak alami', 2, 30),
('MS Glow Lipstick', 65000, 'produk1.jpg', 'Lipstik tahan lama dengan berbagai warna', 3, 25);
```

### 6. Testing Installation

1. Buka browser dan akses: `http://localhost/MSglowstore/msglow/`
2. Pastikan halaman utama dapat diakses
3. Test fitur-fitur utama:
   - User registration
   - User login
   - Product catalog
   - Shopping cart
   - Checkout process
   - Admin panel (http://localhost/MSglowstore/msglow/admin/)

### 7. Admin Panel Setup

#### Default Admin Credentials
- **URL**: `http://localhost/MSglowstore/msglow/login.php`
- **Email**: admin@msglow.com
- **Password**: admin123

#### Change Default Password
1. Login ke admin panel
2. Pergi ke user management
3. Update password admin default

### 8. Troubleshooting

#### Common Issues

**Database Connection Error:**
- Pastikan MySQL server berjalan
- Periksa credentials di `config/database.php`
- Pastikan database `msglow_store` sudah dibuat

**File Upload Issues:**
- Periksa permission folder `uploads/`
- Pastikan `php.ini` mengizinkan file upload
- Set `upload_max_filesize` dan `post_max_size` yang cukup

**Page Not Found (404):**
- Pastikan mod_rewrite enabled di Apache
- Periksa file `.htaccess` jika ada
- Verifikasi path aplikasi di web server

**PHP Errors:**
- Enable error reporting di `php.ini`
- Check PHP version compatibility
- Pastikan semua required extensions installed

#### Enable Error Reporting
Tambahkan di awal file PHP untuk debugging:

```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

#### Check PHP Extensions
Pastikan extensions berikut sudah enabled:
- mysqli
- gd (untuk image processing)
- mbstring
- json
- session

### 9. Security Considerations

#### Production Deployment
- Ganti password default
- Disable error reporting
- Set proper file permissions
- Use HTTPS
- Regular security updates

#### Environment Variables
Consider using environment variables untuk sensitive data:
```php
$database = getenv('DB_DATABASE') ?: 'msglow_store';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
```

### 10. Next Steps

Setelah instalasi berhasil:
1. Baca dokumentasi [USAGE.md](USAGE.md) untuk panduan penggunaan
2. Lihat [DATABASE.md](DATABASE.md) untuk detail database
3. Ikuti [DEPLOYMENT.md](DEPLOYMENT.md) untuk deployment production

---

## Support

Jika mengalami masalah:
1. Periksa log error di web server
2. Pastikan semua requirements terpenuhi
3. Hubungi tim development untuk bantuan lebih lanjut

---

*Last updated: July 2025*
*Version: 1.0.0*
