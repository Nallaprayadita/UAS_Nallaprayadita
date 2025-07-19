# MS Glow Store - Sistem Checkout

## Modifikasi yang Telah Dilakukan

### 1. Halaman Riwayat Pesanan (index.php)
- **Tabel telah dimodifikasi** dengan kolom:
  - No
  - Tanggal (format dd/mm/yyyy)
  - Jenis Pesanan (nama produk)
  - Jumlah (total item)
  - Total Harga
  - Aksi (tombol Checkout)

### 2. Halaman Checkout (checkout.php)
- **Informasi Alamat Pengiriman**:
  - Nama Lengkap (readonly)
  - Email (readonly)
  - No. Telepon (readonly)
  - Alamat Pengiriman (editable)

- **Detail Produk**:
  - Gambar produk
  - Nama produk
  - Harga satuan
  - Jumlah
  - Total per item

- **Opsi Pengiriman**:
  - Reguler (3-5 hari) - Gratis
  - Express (1-2 hari) - Rp 15.000
  - Instant (Hari ini) - Rp 25.000

- **Metode Pembayaran**:
  - Cash on Delivery (COD)
  - Transfer Bank
  - E-Wallet

- **Catatan**: Field opsional untuk catatan pelanggan

### 3. Halaman Konfirmasi (confirmation.php)
- **Menampilkan semua informasi** yang telah diisi di halaman checkout
- **Informasi Pengiriman**: Nama, email, telepon, alamat
- **Detail Produk**: Lengkap dengan gambar dan harga
- **Opsi Pengiriman & Pembayaran**: Pilihan yang dipilih
- **Total Pesanan**: Subtotal + ongkos kirim
- **Tombol Aksi**:
  - Konfirmasi Pesanan (lanjut ke thank you page)
  - Kembali ke Checkout (kembali ke halaman sebelumnya)

### 4. Halaman Terima Kasih (thank_you.php)
- **Pesan terima kasih** yang menarik dengan animasi
- **Informasi status pesanan**
- **Langkah-langkah selanjutnya**
- **Tombol navigasi** untuk lihat pesanan atau belanja lagi
- **Auto redirect** ke halaman pesanan setelah 10 detik (dengan konfirmasi)

## Fitur Tambahan

### Styling dan UX
- **Desain konsisten** dengan tema MS Glow Store
- **Warna utama**: Pink (#d63384) dan putih
- **Responsive design** untuk mobile dan desktop
- **Animasi dan transisi** yang smooth
- **Loading states** dan feedback visual

### Fungsionalitas
- **Session management** untuk data checkout
- **Real-time calculation** ongkos kirim
- **Data validation** di semua form
- **Error handling** dan redirect yang proper

## Instalasi dan Setup

### 1. Database
Jalankan script SQL di `database_update.sql` untuk menambahkan kolom yang diperlukan:

```sql
-- Menambahkan kolom untuk checkout
ALTER TABLE orders ADD COLUMN shipping_address TEXT;
ALTER TABLE orders ADD COLUMN shipping_method VARCHAR(50) DEFAULT 'reguler';
ALTER TABLE orders ADD COLUMN payment_method VARCHAR(50) DEFAULT 'cod';
ALTER TABLE orders ADD COLUMN notes TEXT;

-- Menambahkan kolom untuk user
ALTER TABLE users ADD COLUMN phone VARCHAR(20);
ALTER TABLE users ADD COLUMN address TEXT;
```

### 2. File Structure
```
msglow/orders/
├── index.php           # Halaman riwayat pesanan
├── checkout.php        # Halaman checkout
├── confirmation.php    # Halaman konfirmasi
├── thank_you.php      # Halaman terima kasih
├── database_update.sql # Script update database
└── README.md          # Dokumentasi ini
```

### 3. Flow Aplikasi
1. **Riwayat Pesanan** → Tombol Checkout
2. **Checkout** → Isi detail → Lanjutkan ke Konfirmasi
3. **Konfirmasi** → Review → Konfirmasi Pesanan
4. **Terima Kasih** → Selesai

## Catatan Penting

1. **Pastikan database memiliki data user** dengan kolom phone dan address
2. **Folder uploads** harus ada untuk gambar produk
3. **Session harus aktif** untuk semua halaman
4. **File config/database.php** harus sudah terkonfigurasi dengan benar

## Customization

### Mengubah Ongkos Kirim
Edit di file `checkout.php` dan `confirmation.php` bagian JavaScript:

```javascript
if (this.value === 'express') {
    shippingCost = 15000;  // Ubah nilai ini
}
```

### Mengubah Metode Pembayaran
Edit array `$payment_methods` di file `confirmation.php`:

```php
$payment_methods = [
    'cod' => 'Cash on Delivery (COD)',
    'transfer' => 'Transfer Bank',
    'ewallet' => 'E-Wallet'
];
```

### Mengubah Warna Tema
Edit CSS di bagian `:root` atau langsung di property CSS:

```css
:root {
    --primary-color: #d63384;
    --hover-color: #ff1493;
}
```

## Support

Jika ada pertanyaan atau masalah, silakan hubungi developer atau buka issue di repository ini.
