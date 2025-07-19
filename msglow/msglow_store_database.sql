-- MS Glow Store Database
-- Struktur database lengkap untuk aplikasi MS Glow Store

-- Create database
CREATE DATABASE IF NOT EXISTS msglow_store;
USE msglow_store;

-- Set foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- TABLE 1: categories
-- ============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE 2: users
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE 3: products
-- ============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    category_id INT,
    stock INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- ============================================
-- TABLE 4: payment_methods
-- ============================================
CREATE TABLE IF NOT EXISTS payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    method_name VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE 5: shipping_methods
-- ============================================
CREATE TABLE IF NOT EXISTS shipping_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    cost DECIMAL(10,2) NOT NULL,
    estimated_days VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE 6: orders
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'paid', 'shipped', 'delivered', 'canceled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    shipping_address TEXT,
    shipping_method_id INT,
    payment_method_id INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (shipping_method_id) REFERENCES shipping_methods(id) ON DELETE SET NULL,
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE SET NULL
);

-- ============================================
-- TABLE 7: order_items
-- ============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE 8: product_reviews
-- ============================================
CREATE TABLE IF NOT EXISTS product_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product_review (user_id, product_id)
);

-- ============================================
-- TABLE 9: coupons
-- ============================================
CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_type ENUM('percentage', 'fixed') DEFAULT 'percentage',
    discount_value DECIMAL(10,2) NOT NULL,
    min_order_amount DECIMAL(10,2) DEFAULT 0,
    max_discount_amount DECIMAL(10,2) DEFAULT NULL,
    usage_limit INT DEFAULT NULL,
    used_count INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE 10: cart
-- ============================================
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);

-- Reset foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- SAMPLE DATA
-- ============================================

-- Insert sample categories
INSERT INTO categories (id, name, description) VALUES
(1, 'Produk Wajah', 'Produk perawatan khusus untuk wajah'),
(2, 'Produk Kulit', 'Produk perawatan untuk kulit tubuh'),
(3, 'Produk Tubuh', 'Produk perawatan untuk seluruh tubuh')
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    description = VALUES(description);

-- Insert sample products
INSERT INTO products (id, name, price, image, description, category_id, stock) VALUES
(1, 'MS Glow Serum Vitamin C', 150000, 'produk1.jpg', 'Serum wajah dengan kandungan vitamin C untuk mencerahkan kulit', 1, 50),
(2, 'MS Glow Facial Wash', 75000, 'produk2.jpg', 'Pembersih wajah lembut untuk kulit berjerawat', 1, 100),
(3, 'MS Glow Moisturizer', 120000, 'produk3.jpg', 'Pelembap wajah untuk kulit kering', 1, 80),
(4, 'MS Glow Whitening Serum', 180000, 'produk4.jpg', 'Serum pemutih untuk kulit kusam', 2, 30),
(5, 'MS Glow Body Lotion', 90000, 'produk5.jpg', 'Lotion tubuh untuk kulit halus dan lembut', 3, 60),
(6, 'MS Glow Sunscreen', 95000, 'produk6.jpg', 'Tabir surya untuk melindungi kulit dari UV', 1, 70),
(7, 'MS Glow Night Cream', 165000, 'produk7.jpg', 'Krim malam untuk regenerasi kulit', 1, 40),
(8, 'MS Glow Toner', 85000, 'produk8.jpg', 'Toner untuk mengecilkan pori-pori', 1, 90),
(9, 'MS Glow Exfoliating Scrub', 110000, 'produk9.jpg', 'Scrub untuk mengangkat sel kulit mati', 2, 35),
(10, 'MS Glow Eye Cream', 140000, 'produk10.jpg', 'Krim mata untuk mengurangi kerutan', 1, 45),
(11, 'MS Glow Acne Treatment', 125000, 'produk11.jpg', 'Treatment khusus untuk jerawat', 1, 55),
(12, 'MS Glow Lip Balm', 35000, 'produk12.jpg', 'Pelembap bibir dengan SPF', 1, 150),
(13, 'MS Glow Hand Cream', 45000, 'produk13.jpg', 'Krim tangan untuk kelembapan ekstra', 3, 120),
(14, 'MS Glow Micellar Water', 65000, 'produk14.jpg', 'Pembersih makeup tanpa bilas', 1, 85),
(15, 'MS Glow Body Scrub', 95000, 'produk15.jpg', 'Scrub tubuh untuk kulit halus', 3, 40),
(16, 'MS Glow BB Cream', 110000, 'produk16.jpg', 'BB cream untuk coverage natural', 1, 65)
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    price = VALUES(price),
    image = VALUES(image),
    description = VALUES(description),
    category_id = VALUES(category_id),
    stock = VALUES(stock);

-- Insert sample payment methods
INSERT INTO payment_methods (id, method_name) VALUES
(1, 'Cash on Delivery (COD)'),
(2, 'Transfer Bank'),
(3, 'E-Wallet (OVO/GoPay/Dana)'),
(4, 'Credit Card'),
(5, 'Virtual Account')
ON DUPLICATE KEY UPDATE method_name = VALUES(method_name);

-- Insert sample shipping methods
INSERT INTO shipping_methods (id, name, cost, estimated_days) VALUES
(1, 'Reguler', 15000, '3-5 hari'),
(2, 'Express', 25000, '1-2 hari'),
(3, 'Same Day', 35000, 'Hari yang sama'),
(4, 'Cargo', 20000, '5-7 hari')
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    cost = VALUES(cost),
    estimated_days = VALUES(estimated_days);

-- Insert sample admin user (password: admin123)
INSERT INTO users (id, name, email, password, role, email_verified) VALUES
(1, 'Administrator', 'admin@msglow.com', '$2a$12$EhizkQ7ugF6DvxbOjhUc/OkXuzemQ2f9GlWqVeEjIcLrey3M9R6QC', 'admin', TRUE)
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    email = VALUES(email),
    password = VALUES(password),
    role = VALUES(role),
    email_verified = VALUES(email_verified);

-- Insert sample regular users (password: user123)
INSERT INTO users (id, name, email, password, phone, address, role) VALUES
(2, 'user1', 'user1@msglow.com', '$2a$12$53kMcus8.1jJ3Vb0CFj/e.TB.5x7sWvh2X9HLsCTDztcw6K3JiYtq', '081234567890', 'Jl. Contoh No. 123, Jakarta', 'user'),
(3, 'Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'Jl. Contoh No. 456, Bandung', 'user'),
(4, 'Bob Wilson', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', 'Jl. Contoh No. 789, Surabaya', 'user')
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    email = VALUES(email),
    password = VALUES(password),
    phone = VALUES(phone),
    address = VALUES(address),
    role = VALUES(role);

-- Insert sample coupons
INSERT INTO coupons (id, code, discount_type, discount_value, min_order_amount, expires_at) VALUES
(1, 'WELCOME10', 'percentage', 10.00, 100000, '2024-12-31 23:59:59'),
(2, 'NEWUSER20', 'percentage', 20.00, 200000, '2024-12-31 23:59:59'),
(3, 'SAVE50K', 'fixed', 50000, 500000, '2024-12-31 23:59:59')
ON DUPLICATE KEY UPDATE 
    code = VALUES(code),
    discount_type = VALUES(discount_type),
    discount_value = VALUES(discount_value),
    min_order_amount = VALUES(min_order_amount),
    expires_at = VALUES(expires_at);

-- Insert sample product reviews
INSERT INTO product_reviews (product_id, user_id, rating, comment, is_approved) VALUES
(1, 2, 5, 'Produk sangat bagus, kulit jadi lebih cerah!', TRUE),
(2, 3, 4, 'Facial wash yang lembut dan tidak bikin kering', TRUE),
(3, 4, 5, 'Moisturizer favorit saya, sangat melembapkan', TRUE),
(1, 3, 4, 'Serum vitamin C terbaik yang pernah saya coba', TRUE),
(4, 2, 5, 'Whitening serum bekerja dengan baik', TRUE)
ON DUPLICATE KEY UPDATE 
    rating = VALUES(rating),
    comment = VALUES(comment),
    is_approved = VALUES(is_approved);

-- Insert sample orders
INSERT INTO orders (id, user_id, total, status, shipping_address, shipping_method_id, payment_method_id, notes) VALUES
(1, 2, 225000, 'delivered', 'Jl. Contoh No. 123, Jakarta', 1, 1, 'Tolong kirim sore hari'),
(2, 3, 195000, 'shipped', 'Jl. Contoh No. 456, Bandung', 2, 2, 'Barang sudah dibayar via transfer'),
(3, 4, 305000, 'processing', 'Jl. Contoh No. 789, Surabaya', 1, 3, 'Pesanan untuk hadiah')
ON DUPLICATE KEY UPDATE 
    total = VALUES(total),
    status = VALUES(status),
    shipping_address = VALUES(shipping_address),
    shipping_method_id = VALUES(shipping_method_id),
    payment_method_id = VALUES(payment_method_id),
    notes = VALUES(notes);

-- Insert sample order items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 150000),
(1, 2, 1, 75000),
(2, 3, 1, 120000),
(2, 2, 1, 75000),
(3, 4, 1, 180000),
(3, 5, 1, 90000),
(3, 6, 1, 95000)
ON DUPLICATE KEY UPDATE 
    quantity = VALUES(quantity),
    price = VALUES(price);

-- ============================================
-- INDEXES FOR BETTER PERFORMANCE
-- ============================================

-- Index for products search
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_price ON products(price);

-- Index for orders
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_date ON orders(order_date);

-- Index for users
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- Index for reviews
CREATE INDEX idx_reviews_product ON product_reviews(product_id);
CREATE INDEX idx_reviews_rating ON product_reviews(rating);

-- Index for coupons
CREATE INDEX idx_coupons_code ON coupons(code);
CREATE INDEX idx_coupons_active ON coupons(is_active);

-- ============================================
-- VIEWS FOR COMMON QUERIES
-- ============================================

-- View for product details with category
CREATE OR REPLACE VIEW product_details AS
SELECT 
    p.id,
    p.name,
    p.price,
    p.image,
    p.description,
    p.stock,
    p.is_active,
    c.name AS category_name,
    AVG(pr.rating) AS avg_rating,
    COUNT(pr.id) AS review_count
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
LEFT JOIN product_reviews pr ON p.id = pr.product_id AND pr.is_approved = TRUE
GROUP BY p.id, p.name, p.price, p.image, p.description, p.stock, p.is_active, c.name;

-- View for order summary
CREATE OR REPLACE VIEW order_summary AS
SELECT 
    o.id,
    o.user_id,
    u.name AS user_name,
    u.email AS user_email,
    o.total,
    o.status,
    o.order_date,
    o.shipping_address,
    sm.name AS shipping_method,
    pm.method_name AS payment_method,
    COUNT(oi.id) AS item_count
FROM orders o
JOIN users u ON o.user_id = u.id
LEFT JOIN shipping_methods sm ON o.shipping_method_id = sm.id
LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
LEFT JOIN order_items oi ON o.id = oi.order_id
GROUP BY o.id, o.user_id, u.name, u.email, o.total, o.status, o.order_date, o.shipping_address, sm.name, pm.method_name;

-- ============================================
-- TRIGGERS FOR AUTOMATIC UPDATES
-- ============================================

-- Trigger to update product stock after order
DELIMITER $$
CREATE TRIGGER update_stock_after_order
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock - NEW.quantity 
    WHERE id = NEW.product_id;
END$$
DELIMITER ;

-- Trigger to restore product stock when order is canceled
DELIMITER $$
CREATE TRIGGER restore_stock_on_cancel
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.status != 'canceled' AND NEW.status = 'canceled' THEN
        UPDATE products p
        JOIN order_items oi ON p.id = oi.product_id
        SET p.stock = p.stock + oi.quantity
        WHERE oi.order_id = NEW.id;
    END IF;
END$$
DELIMITER ;

-- ============================================
-- STORED PROCEDURES
-- ============================================

-- Procedure to get product recommendations
DELIMITER $$
CREATE PROCEDURE GetProductRecommendations(IN user_id INT, IN limit_count INT)
BEGIN
    SELECT DISTINCT p.id, p.name, p.price, p.image, p.description, AVG(pr.rating) as avg_rating
    FROM products p
    LEFT JOIN product_reviews pr ON p.id = pr.product_id
    WHERE p.is_active = TRUE
    AND p.id IN (
        SELECT DISTINCT p2.id
        FROM products p2
        JOIN order_items oi ON p2.id = oi.product_id
        JOIN orders o ON oi.order_id = o.id
        WHERE o.user_id = user_id
        AND p2.category_id IN (
            SELECT DISTINCT p3.category_id
            FROM products p3
            JOIN order_items oi2 ON p3.id = oi2.product_id
            JOIN orders o2 ON oi2.order_id = o2.id
            WHERE o2.user_id = user_id
        )
    )
    GROUP BY p.id, p.name, p.price, p.image, p.description
    ORDER BY avg_rating DESC, p.created_at DESC
    LIMIT limit_count;
END$$
DELIMITER ;

-- Procedure to calculate order total with coupon
DELIMITER $$
CREATE PROCEDURE CalculateOrderTotal(IN order_id INT, IN coupon_code VARCHAR(50))
BEGIN
    DECLARE subtotal DECIMAL(10,2);
    DECLARE discount DECIMAL(10,2) DEFAULT 0;
    DECLARE final_total DECIMAL(10,2);
    
    -- Calculate subtotal
    SELECT SUM(quantity * price) INTO subtotal
    FROM order_items
    WHERE order_id = order_id;
    
    -- Apply coupon if valid
    IF coupon_code IS NOT NULL AND coupon_code != '' THEN
        SELECT 
            CASE 
                WHEN discount_type = 'percentage' THEN 
                    LEAST(subtotal * (discount_value / 100), IFNULL(max_discount_amount, subtotal))
                WHEN discount_type = 'fixed' THEN 
                    LEAST(discount_value, subtotal)
                ELSE 0
            END INTO discount
        FROM coupons
        WHERE code = coupon_code 
        AND is_active = TRUE
        AND (expires_at IS NULL OR expires_at > NOW())
        AND subtotal >= min_order_amount;
    END IF;
    
    SET final_total = subtotal - discount;
    
    -- Update order total
    UPDATE orders 
    SET total = final_total 
    WHERE id = order_id;
    
    SELECT subtotal, discount, final_total;
END$$
DELIMITER ;

-- Success message
SELECT 'MS Glow Store database has been created successfully with 10 tables and sample data!' AS message;
