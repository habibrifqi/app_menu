# Dokumentasi Aplikasi Pemesanan Makanan

## Overview
Aplikasi pemesanan makanan berbasis QR Code yang memungkinkan pelanggan untuk memesan makanan dengan cara scan barcode di meja, memilih menu, checkout, hingga makanan diantar ke meja.

**Tech Stack:**
- Backend: Laravel (PHP)
- Frontend: Vue.js
- Database: PostgreSQL
- Authentication: Laravel Sanctum

---

## Alur Aplikasi (User Flow)

### 1. Scan QR Code
- Customer scan QR code yang ada di meja restoran
- QR code berisi URL dengan parameter `meja_id` dan `restoran_id`
- Format: `https://app.domain.com/menu?meja={meja_id}&restoran={restoran_id}`

### 2. Halaman Menu
- Customer diarahkan ke halaman menu (web app/native)
- Tampilkan informasi meja dan restoran
- Tampilkan daftar kategori dan item menu
- Customer dapat browse menu berdasarkan kategori

### 3. Pilih Item
- Customer memilih item dari menu
- Dapat menambahkan catatan khusus per item
- Dapat mengatur quantity
- Item masuk ke keranjang

### 4. Keranjang
- Review semua item yang dipilih
- Edit quantity atau hapus item
- Lihat subtotal per item dan total keseluruhan
- Tambahkan catatan umum untuk pesanan

### 5. Checkout
- Pilih metode pembayaran:
  - **Online Payment**: Bayar langsung via payment gateway
  - **Cash**: Bayar nanti di kasir
- Konfirmasi detail pesanan
- Submit order

### 6. Konfirmasi Pesanan
- Order berhasil dibuat dengan status awal:
  - Jika online payment: `menunggu_pembayaran`
  - Jika cash: `menunggu_konfirmasi_kasir`
- Customer mendapat notifikasi order berhasil
- Tampilkan nomor order dan estimasi waktu

### 7. Proses Pesanan (Backend)
**Status Flow:**
1. `menunggu_pembayaran` → Menunggu pembayaran online
2. `menunggu_konfirmasi_kasir` → Kasir konfirmasi pesanan cash
3. `diproses` → Dapur mulai memasak
4. `siap_antar` → Makanan siap diantar
5. `selesai` → Makanan sudah diantar ke meja
6. `dibatalkan` → Pesanan dibatalkan

### 8. Makanan Diantar
- Waiter mengantarkan makanan ke meja
- Update status menjadi `selesai`
- Customer dapat memberikan rating/feedback (future feature)

---

## Database Schema

### 1. Tabel `restaurants`
Menyimpan data restoran.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| nama | varchar | Nama restoran |
| alamat | text | Alamat restoran |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2. Tabel `mejas`
Menyimpan data meja restoran.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| restoran_id | bigint (FK) | ID restoran |
| nomor_meja | varchar | Nomor meja (A1, B2, dll) |
| qr_code | varchar (nullable) | Kode unik untuk QR |
| created_at | timestamp | |
| updated_at | timestamp | |

**Relasi:** `mejas.restoran_id` → `restaurants.id` (cascade delete)

### 3. Tabel `kategoris`
Menyimpan kategori menu (makanan, minuman, dll).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| restoran_id | bigint (FK) | ID restoran |
| nama | varchar | Nama kategori |
| urutan | integer | Untuk sorting kategori |
| created_at | timestamp | |
| updated_at | timestamp | |

**Relasi:** `kategoris.restoran_id` → `restaurants.id` (cascade delete)

### 4. Tabel `items`
Menyimpan data menu item.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| name | varchar(100) | Nama item |
| price | integer | Harga item |
| image | varchar | Path gambar item |
| kategori_id | bigint (FK) | ID kategori |
| deleted_at | timestamp (nullable) | Soft delete |
| created_at | timestamp | |
| updated_at | timestamp | |

**Relasi:** `items.kategori_id` → `kategoris.id` (cascade delete)

### 5. Tabel `orders`
Menyimpan data pesanan.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| customer_name | varchar | Nama customer |
| table_no | varchar(5) | Nomor meja (deprecated, gunakan meja_id) |
| order_date | date | Tanggal order (default: today) |
| ordertime | time | Waktu order (default: now) |
| total | integer | Total harga |
| waiters_id | bigint (FK, nullable) | ID waiter |
| cashiers_id | bigint (FK, nullable) | ID kasir |
| meja_id | bigint (FK) | ID meja |
| status | enum | Status pesanan |
| metode_pembayaran | enum (nullable) | cash / online |
| payment_status | enum | lunas / belum_lunas (default: belum_lunas) |
| catatan_umum | text (nullable) | Catatan untuk pesanan |
| deleted_at | timestamp (nullable) | Soft delete |
| created_at | timestamp | |
| updated_at | timestamp | |

**Status Enum:**
- `menunggu_pembayaran`
- `menunggu_konfirmasi_kasir`
- `diproses`
- `siap_antar`
- `selesai`
- `dibatalkan`

**Relasi:**
- `orders.waiters_id` → `users.id` (cascade delete)
- `orders.cashiers_id` → `users.id` (cascade delete)
- `orders.meja_id` → `mejas.id` (cascade delete)

### 6. Tabel `order_details`
Menyimpan detail item dalam pesanan.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| order_id | bigint (FK) | ID order |
| item_id | bigint (FK) | ID item |
| quantity | integer | Jumlah item |
| harga_satuan | decimal(12,2) | Harga per item (snapshot) |
| catatan | text (nullable) | Catatan khusus item |
| subtotal | decimal(14,2) | Total harga (quantity × harga_satuan) |
| deleted_at | timestamp (nullable) | Soft delete |
| created_at | timestamp | |
| updated_at | timestamp | |

**Relasi:**
- `order_details.order_id` → `orders.id` (cascade delete)
- `order_details.item_id` → `items.id` (cascade delete)

### 7. Tabel `riwayat_status`
Menyimpan riwayat perubahan status pesanan (audit trail).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| orders_id | bigint (FK) | ID order |
| status | varchar | Status yang dicapai |
| timestamp | timestamp | Waktu perubahan status |
| created_at | timestamp | |
| updated_at | timestamp | |

**Relasi:** `riwayat_status.orders_id` → `orders.id` (cascade delete)

### 8. Tabel `users`
Menyimpan data user (waiter, kasir, admin).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| name | varchar | Nama user |
| email | varchar (unique) | Email user |
| password | varchar | Password (hashed) |
| role_id | bigint (FK) | ID role |
| created_at | timestamp | |
| updated_at | timestamp | |

### 9. Tabel `roles`
Menyimpan data role user.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| name | varchar | Nama role (admin, waiter, kasir) |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## API Endpoints

### Authentication

#### Login
```
POST /api/login
Content-Type: application/json

Request:
{
  "email": "user@example.com",
  "password": "password"
}

Response:
{
  "status": "success",
  "message": "Login successful",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com"
  }
}
```

#### Get Current User
```
GET /api/me
Authorization: Bearer {token}

Response:
{
  "id": 1,
  "name": "User Name",
  "email": "user@example.com",
  "role_id": 1
}
```

### Items (Menu)

#### Create Item
```
POST /api/items
Authorization: Bearer {token}
Content-Type: multipart/form-data

Request:
- name: "Nasi Goreng"
- price: 25000
- image: [file upload]
- kategori_id: 1

Response:
{
  "status": "success",
  "message": "Item created successfully",
  "data": {
    "id": 1,
    "name": "Nasi Goreng",
    "price": 25000,
    "image": "items/abc123.jpg",
    "image_url": "http://localhost:8000/storage/items/abc123.jpg",
    "kategori_id": 1
  }
}
```

#### Update Item
```
PUT /api/items/{id}
Authorization: Bearer {token}
Content-Type: multipart/form-data

Request (all fields optional):
- name: "Nasi Goreng Special"
- price: 30000
- image: [file upload]
- kategori_id: 1

Response:
{
  "status": "success",
  "message": "Item updated successfully",
  "data": {
    "id": 1,
    "name": "Nasi Goreng Special",
    "price": 30000,
    "image": "items/xyz789.jpg",
    "image_url": "http://localhost:8000/storage/items/xyz789.jpg",
    "kategori_id": 1
  }
}
```

### Orders

#### Create Order
```
POST /api/orders
Authorization: Bearer {token}
Content-Type: application/json

Request:
{
  "customer_name": "John Doe",
  "table_no": "A1",
  "meja_id": 1,
  "status": "menunggu_pembayaran",
  "metode_pembayaran": "online",
  "catatan_umum": "Pedas sedang",
  "waiters_id": 1,
  "cashiers_id": null,
  "items": [
    {
      "item_id": 1,
      "quantity": 2,
      "catatan": "Tanpa bawang"
    },
    {
      "item_id": 2,
      "quantity": 1,
      "catatan": null
    }
  ]
}

Response:
{
  "status": "success",
  "message": "Order created successfully",
  "data": {
    "id": 1,
    "customer_name": "John Doe",
    "table_no": "A1",
    "meja_id": 1,
    "order_date": "2026-02-19",
    "ordertime": "14:30:00",
    "status": "menunggu_pembayaran",
    "metode_pembayaran": "online",
    "payment_status": "belum_lunas",
    "total": 55000,
    "waiter": {
      "id": 1,
      "name": "Waiter Name"
    },
    "cashier": null,
    "items": [
      {
        "id": 1,
        "item_id": 1,
        "item_name": "Nasi Goreng",
        "quantity": 2,
        "harga_satuan": 25000,
        "catatan": "Tanpa bawang",
        "subtotal": 50000
      },
      {
        "id": 2,
        "item_id": 2,
        "item_name": "Es Teh",
        "quantity": 1,
        "harga_satuan": 5000,
        "catatan": null,
        "subtotal": 5000
      }
    ]
  }
}
```

---

## Frontend (Vue.js) Structure

### Recommended Pages

1. **Landing Page** (`/`)
   - Redirect ke menu jika ada QR params
   - Info aplikasi

2. **Menu Page** (`/menu?meja={id}&restoran={id}`)
   - Display restaurant info
   - Display table number
   - List categories
   - List items by category
   - Add to cart functionality

3. **Cart Page** (`/cart`)
   - Review cart items
   - Edit quantity
   - Remove items
   - Add general notes
   - Show total
   - Proceed to checkout

4. **Checkout Page** (`/checkout`)
   - Customer name input
   - Payment method selection
   - Order summary
   - Submit order

5. **Order Status Page** (`/order/{id}`)
   - Display order details
   - Real-time status updates
   - Order timeline

6. **Admin Dashboard** (`/admin`)
   - Manage restaurants
   - Manage categories
   - Manage items
   - Manage tables
   - Generate QR codes

7. **Kitchen Dashboard** (`/kitchen`)
   - View incoming orders
   - Update order status
   - Mark as ready

8. **Cashier Dashboard** (`/cashier`)
   - View pending payments
   - Confirm cash payments
   - Payment history

---

## Setup & Installation

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & npm
- PostgreSQL

### Backend Setup (Laravel)

1. Clone repository
```bash
git clone <repository-url>
cd aplikasi-menu
```

2. Install dependencies
```bash
composer install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database di `.env`
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=aplikasi_menu
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

5. Run migrations
```bash
php artisan migrate
```

6. Create storage link
```bash
php artisan storage:link
```

7. Run seeder (optional)
```bash
php artisan db:seed
```

8. Start server
```bash
php artisan serve
```

### Frontend Setup (Vue.js)

1. Install dependencies
```bash
npm install
```

2. Configure API endpoint
```javascript
// .env or config file
VITE_API_URL=http://localhost:8000/api
```

3. Run development server
```bash
npm run dev
```

4. Build for production
```bash
npm run build
```

---

## QR Code Generation

### Generate QR Code untuk Meja

Gunakan library seperti `simple-qrcode` atau `endroid/qr-code`:

```bash
composer require simplesoftwareio/simple-qrcode
```

```php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Generate QR Code
$url = "https://app.domain.com/menu?meja={$meja->id}&restoran={$restoran->id}";
$qrCode = QrCode::size(300)->generate($url);

// Save to storage
QrCode::format('png')
    ->size(300)
    ->generate($url, public_path("qrcodes/meja-{$meja->id}.png"));
```

---

## Payment Integration (Future)

### Online Payment Options
1. **Midtrans** (Indonesia)
2. **Xendit** (Indonesia)
3. **Stripe** (International)
4. **PayPal** (International)

### Implementation Flow
1. Customer pilih online payment
2. Create payment transaction via payment gateway
3. Redirect ke payment page
4. Handle payment callback/webhook
5. Update order status dan payment_status

---

## Real-time Updates (Future)

### WebSocket / Pusher
Untuk real-time status updates:

1. Install Laravel WebSockets atau Pusher
```bash
composer require beyondcode/laravel-websockets
```

2. Broadcast events saat status berubah
```php
event(new OrderStatusUpdated($order));
```

3. Listen di frontend (Vue)
```javascript
Echo.channel(`order.${orderId}`)
    .listen('OrderStatusUpdated', (e) => {
        // Update UI
    });
```

---

## Security Considerations

1. **CORS Configuration**
   - Configure allowed origins di `config/cors.php`

2. **Rate Limiting**
   - Implement rate limiting untuk API endpoints

3. **Input Validation**
   - Validate semua input di controller

4. **SQL Injection Prevention**
   - Gunakan Eloquent ORM (sudah built-in protection)

5. **XSS Prevention**
   - Sanitize output di frontend

6. **CSRF Protection**
   - Laravel Sanctum untuk API authentication

---

## Testing

### Backend Testing
```bash
php artisan test
```

### Frontend Testing
```bash
npm run test
```

---

## Deployment

### Backend (Laravel)
1. Setup production server (Ubuntu/Nginx)
2. Configure PostgreSQL
3. Setup environment variables
4. Run migrations
5. Configure web server
6. Setup SSL certificate

### Frontend (Vue.js)
1. Build production assets
```bash
npm run build
```
2. Deploy to CDN atau static hosting
3. Configure environment variables

---

## Maintenance

### Database Backup
```bash
pg_dump aplikasi_menu > backup.sql
```

### Log Monitoring
```bash
tail -f storage/logs/laravel.log
```

### Performance Optimization
1. Cache configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. Database indexing
3. Query optimization
4. Image optimization

---

## Support & Documentation

- Laravel Documentation: https://laravel.com/docs
- Vue.js Documentation: https://vuejs.org/guide
- PostgreSQL Documentation: https://www.postgresql.org/docs

---

## License
MIT License
