# Database Documentation

## Overview
MS Glow Store menggunakan database MySQL untuk menyimpan data aplikasi. Database ini dirancang untuk mengelola produk kecantikan, pesanan, pengguna, dan sistem pembayaran.

## Database Configuration

### Connection Settings
- **Host**: localhost
- **Username**: root
- **Password**: (kosong)
- **Database Name**: msglow_store

### Connection File
Konfigurasi database dapat ditemukan di: `msglow/config/database.php`

## Database Schema

### Core Tables

#### 1. users
Tabel untuk menyimpan informasi pengguna dan admin.

```sql
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
```

#### 2. products
Tabel untuk menyimpan informasi produk kecantikan.

```sql
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    category_id INT,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 3. categories
Tabel untuk menyimpan kategori produk.

```sql
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 4. orders
Tabel untuk menyimpan informasi pesanan.

```sql
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
```

#### 5. order_items
Tabel untuk menyimpan detail item dalam pesanan.

```sql
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

#### 6. payment_methods
Tabel untuk menyimpan metode pembayaran yang tersedia.

```sql
CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Database Setup

### 1. Create Database
```sql
CREATE DATABASE msglow_store;
USE msglow_store;
```

### 2. Run Migration
Jalankan file SQL yang terdapat di `msglow/database_update.sql` untuk membuat atau memperbarui struktur tabel.

### 3. Seed Data (Optional)
Untuk testing, Anda dapat menambahkan data sample:

```sql
-- Sample categories
INSERT INTO categories (name, description) VALUES
('Skincare Wajah', 'Produk perawatan wajah'),
('Skincare Tubuh', 'Produk perawatan tubuh'),
('Makeup', 'Produk makeup dan kosmetik');

-- Sample payment methods
INSERT INTO payment_methods (name, description) VALUES
('Cash on Delivery (COD)', 'Pembayaran saat barang diterima'),
('Transfer Bank', 'Transfer ke rekening bank'),
('E-Wallet', 'Pembayaran melalui dompet digital');

-- Sample admin user
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@msglow.com', MD5('admin123'), 'admin');
```

## Database Relationships

### Entity Relationship Diagram (ERD)
```
users ||--o{ orders : places
orders ||--o{ order_items : contains
products ||--o{ order_items : includes
categories ||--o{ products : categorizes
```

### Key Relationships
- **users** → **orders**: One-to-Many (satu user dapat memiliki banyak pesanan)
- **orders** → **order_items**: One-to-Many (satu pesanan dapat memiliki banyak item)
- **products** → **order_items**: One-to-Many (satu produk dapat ada di banyak pesanan)
- **categories** → **products**: One-to-Many (satu kategori dapat memiliki banyak produk)

## Indexes and Performance

### Recommended Indexes
```sql
-- Indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_date ON orders(order_date);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_order_items_product_id ON order_items(product_id);
CREATE INDEX idx_products_category_id ON products(category_id);
```

## Data Types and Constraints

### Important Constraints
- **Email uniqueness**: User emails must be unique
- **Foreign Key constraints**: Maintain referential integrity
- **Default values**: Proper default values for status fields
- **NOT NULL constraints**: Critical fields cannot be empty

### Data Validation
- **Email format**: Validate email format in application layer
- **Phone format**: Validate Indonesian phone number format
- **Price validation**: Ensure prices are positive values
- **Stock validation**: Prevent negative stock values

## Backup and Maintenance

### Regular Backup
```bash
# Daily backup
mysqldump -u root -p msglow_store > backup_$(date +%Y%m%d).sql

# Restore from backup
mysql -u root -p msglow_store < backup_file.sql
```

### Database Maintenance
- Regular optimization of tables
- Monitor slow queries
- Update statistics regularly
- Check for unused indexes

## Security Considerations

### Database Security
- Use strong passwords for database users
- Limit database user privileges
- Regular security updates
- Encrypt sensitive data
- Use prepared statements to prevent SQL injection

### Application Security
- Hash passwords using strong algorithms
- Validate all user inputs
- Use parameterized queries
- Implement proper session management

## Troubleshooting

### Common Issues
1. **Connection errors**: Check database credentials and server status
2. **Table doesn't exist**: Run migration scripts
3. **Foreign key constraints**: Ensure referenced data exists
4. **Character encoding**: Use UTF-8 for international characters

### Debug Mode
Enable MySQL query logging for debugging:
```sql
SET GLOBAL general_log = 'ON';
SET GLOBAL log_output = 'TABLE';
```

## Performance Optimization

### Query Optimization
- Use appropriate indexes
- Avoid SELECT * queries
- Use LIMIT for pagination
- Optimize JOIN operations

### Database Configuration
- Adjust buffer pool size
- Configure query cache
- Set appropriate timeout values
- Monitor connection limits

---

*Last updated: July 2025*
*Version: 1.0.0*
