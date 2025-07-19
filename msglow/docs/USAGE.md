# User Guide

## Overview
MS Glow Store adalah aplikasi e-commerce yang memungkinkan pengguna untuk membeli produk kecantikan MS Glow secara online. Aplikasi ini menyediakan fitur lengkap untuk pelanggan dan admin.

## User Interface

### Customer Interface

#### 1. Homepage
- **URL**: `http://localhost/MSglowstore/msglow/`
- **Fitur**:
  - Banner promosi
  - Katalog produk terbaru
  - Kategori produk
  - Testimoni pelanggan
  - Footer dengan informasi kontak

#### 2. Product Catalog
- **Fitur**:
  - Grid view produk dengan gambar
  - Filter berdasarkan kategori
  - Pencarian produk
  - Detail harga dan deskripsi
  - Tombol "Add to Cart"

#### 3. User Registration & Login
- **Registration**: `/register.php`
  - Nama lengkap
  - Email
  - Password
  - Nomor telepon
  - Alamat
- **Login**: `/login.php`
  - Email dan password
  - Remember me option

#### 4. Shopping Cart & Checkout
- **Cart Features**:
  - View items in cart
  - Adjust quantity
  - Remove items
  - Calculate total
- **Checkout Process**:
  - Shipping address
  - Shipping method selection
  - Payment method selection
  - Order confirmation

### Admin Interface

#### 1. Admin Dashboard
- **URL**: `http://localhost/MSglowstore/msglow/dashboard.php`
- **Features**:
  - Sales overview
  - Recent orders
  - Product statistics
  - User management summary

#### 2. Product Management
- **Add Product**: `/products/create.php`
  - Product name
  - Price
  - Image upload
  - Description
  - Category selection
  - Stock quantity
- **Edit Product**: `/products/edit.php?id={product_id}`
- **Delete Product**: `/products/delete.php?id={product_id}`
- **Product List**: `/products/index.php`

#### 3. Category Management
- **Add Category**: `/categories/create.php`
- **Edit Category**: `/categories/edit.php?id={category_id}`
- **Delete Category**: `/categories/delete.php?id={category_id}`
- **Category List**: `/categories/index.php`

#### 4. Order Management
- **View Orders**: `/admin/orders.php`
  - Order list with status
  - Order details
  - Customer information
  - Payment status
- **Update Order Status**:
  - Pending
  - Processing
  - Shipped
  - Delivered
  - Cancelled

#### 5. Payment Method Management
- **Add Payment Method**: `/payment_methods/create.php`
- **Edit Payment Method**: `/payment_methods/edit.php?id={method_id}`
- **Payment Method List**: `/payment_methods/index.php`

## Feature Guide

### For Customers

#### 1. Browse Products
1. Visit homepage
2. Browse featured products or use category filter
3. Click on product for detailed view
4. Check price, description, and availability

#### 2. Make Purchase
1. Add desired products to cart
2. View cart and adjust quantities
3. Proceed to checkout
4. Fill shipping information
5. Select shipping method:
   - **Reguler** (3-5 hari) - Gratis
   - **Express** (1-2 hari) - Rp 15.000
   - **Instant** (Hari ini) - Rp 25.000
6. Choose payment method:
   - Cash on Delivery (COD)
   - Transfer Bank
   - E-Wallet
7. Add order notes (optional)
8. Confirm order

#### 3. Order History
1. Login to account
2. Go to orders page: `/orders/index.php`
3. View order history with details:
   - Order date
   - Products ordered
   - Total amount
   - Order status
   - Tracking information

#### 4. Account Management
1. Update profile information
2. Change password
3. Update shipping address
4. View order history

### For Administrators

#### 1. Product Management
1. **Add New Product**:
   - Navigate to Products → Add New
   - Fill product details
   - Upload product image
   - Set category and stock
   - Save product

2. **Edit Product**:
   - Go to Products list
   - Click Edit on desired product
   - Update information
   - Save changes

3. **Manage Stock**:
   - Monitor stock levels
   - Update quantities
   - Set low stock alerts

#### 2. Order Processing
1. **View New Orders**:
   - Check Orders dashboard
   - Review order details
   - Verify payment status

2. **Process Orders**:
   - Update order status
   - Add tracking information
   - Send order confirmations

3. **Handle Returns**:
   - Process return requests
   - Update order status
   - Handle refunds

#### 3. Customer Management
1. **View Customer List**:
   - Access user management
   - View customer details
   - Check order history

2. **Customer Support**:
   - Respond to inquiries
   - Handle complaints
   - Provide order updates

#### 4. Reports and Analytics
1. **Sales Reports**:
   - Daily/monthly sales
   - Top-selling products
   - Customer analytics

2. **Inventory Reports**:
   - Stock levels
   - Low stock alerts
   - Product performance

## Workflow Examples

### Customer Purchase Workflow
```
1. Browse Products → 2. Add to Cart → 3. Checkout → 4. Payment → 5. Order Confirmation → 6. Delivery
```

### Admin Order Processing Workflow
```
1. New Order Alert → 2. Order Verification → 3. Payment Confirmation → 4. Order Processing → 5. Shipping → 6. Delivery Confirmation
```

## Technical Features

### Security Features
- Password hashing
- Session management
- SQL injection protection
- File upload validation
- Access control

### Performance Features
- Image optimization
- Database indexing
- Caching mechanisms
- Responsive design

### Integration Features
- Payment gateway integration
- Email notifications
- SMS notifications (optional)
- Social media sharing

## Troubleshooting

### Common User Issues

#### 1. Cannot Login
- **Solution**: Check email and password
- Verify account is active
- Reset password if needed

#### 2. Cart Issues
- **Solution**: Clear browser cache
- Check session timeout
- Verify product availability

#### 3. Checkout Problems
- **Solution**: Verify shipping address
- Check payment method selection
- Ensure all required fields are filled

#### 4. Order Status
- **Solution**: Check order history
- Contact customer support
- Verify payment status

### Admin Issues

#### 1. Cannot Access Admin Panel
- **Solution**: Check admin credentials
- Verify admin role in database
- Check session timeout

#### 2. Product Upload Issues
- **Solution**: Check file permissions
- Verify image format and size
- Check upload directory

#### 3. Database Errors
- **Solution**: Check database connection
- Verify table structure
- Check server logs

## Best Practices

### For Customers
1. **Security**:
   - Use strong passwords
   - Logout after shopping
   - Keep account information updated

2. **Shopping**:
   - Read product descriptions carefully
   - Check return policy
   - Save order confirmations

### For Administrators
1. **Product Management**:
   - Keep product information updated
   - Use high-quality images
   - Maintain accurate stock levels

2. **Order Management**:
   - Process orders promptly
   - Update order status regularly
   - Communicate with customers

3. **Security**:
   - Regular password updates
   - Monitor system logs
   - Regular backups

## API Documentation

### Available Endpoints (if applicable)
- `GET /api/products` - Get product list
- `POST /api/orders` - Create new order
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}` - Update order status

### Authentication
- Session-based authentication
- Role-based access control
- CSRF protection

## Support

### Customer Support
- **Email**: support@msglow.com
- **Phone**: +62-XXX-XXXX-XXXX
- **Hours**: 08:00 - 17:00 WIB

### Technical Support
- **Email**: tech@msglow.com
- **Documentation**: See `/docs/` folder
- **Issue Tracking**: GitHub Issues

---

*Last updated: July 2025*
*Version: 1.0.0*
