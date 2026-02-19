# Catatan Pengembangan Aplikasi Pemesanan Makanan

## Yang Sudah Ada ✅

### Database Schema
- ✅ Tabel `restaurants` - Data restoran
- ✅ Tabel `mejas` - Data meja dengan QR code
- ✅ Tabel `kategoris` - Kategori menu
- ✅ Tabel `items` - Menu items dengan gambar
- ✅ Tabel `orders` - Pesanan dengan status flow lengkap
- ✅ Tabel `order_details` - Detail pesanan dengan quantity, harga, catatan
- ✅ Tabel `riwayat_status` - Audit trail status pesanan
- ✅ Tabel `users` - User management
- ✅ Tabel `roles` - Role-based access control

### Models
- ✅ `Item` model dengan relasi ke kategori
- ✅ `Order` model dengan relasi ke user (waiter, cashier)
- ✅ `OrderDetail` model dengan relasi ke order dan item

### API Endpoints
- ✅ Authentication (login, me)
- ✅ Items CRUD (create, update) dengan upload gambar
- ✅ Orders create dengan auto-calculate total

### Features
- ✅ Image upload untuk menu items
- ✅ Soft deletes untuk items, orders, order_details
- ✅ Status flow untuk pesanan
- ✅ Payment method (cash/online)
- ✅ Payment status tracking

---

## Yang Masih Kurang ⚠️

### 1. Models yang Belum Dibuat

#### Restaurant Model
```php
// app/Models/Restaurant.php
class Restaurant extends Model
{
    protected $fillable = ['nama', 'alamat'];
    
    public function mejas()
    {
        return $this->hasMany(Meja::class, 'restoran_id');
    }
    
    public function kategoris()
    {
        return $this->hasMany(Kategori::class, 'restoran_id');
    }
}
```

#### Meja Model
```php
// app/Models/Meja.php
class Meja extends Model
{
    protected $fillable = ['restoran_id', 'nomor_meja', 'qr_code'];
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restoran_id');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'meja_id');
    }
}
```

#### Kategori Model
```php
// app/Models/Kategori.php
class Kategori extends Model
{
    protected $fillable = ['restoran_id', 'nama', 'urutan'];
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restoran_id');
    }
    
    public function items()
    {
        return $this->hasMany(Item::class, 'kategori_id');
    }
}
```

#### RiwayatStatus Model
```php
// app/Models/RiwayatStatus.php
class RiwayatStatus extends Model
{
    protected $table = 'riwayat_status';
    protected $fillable = ['orders_id', 'status', 'timestamp'];
    
    protected $casts = [
        'timestamp' => 'datetime',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}
```

---

### 2. Update Model yang Sudah Ada

#### Item Model - Tambahkan Relasi
```php
// Tambahkan di app/Models/Item.php
public function kategori()
{
    return $this->belongsTo(Kategori::class, 'kategori_id');
}
```

#### Order Model - Update Relasi
```php
// Tambahkan di app/Models/Order.php
public function meja()
{
    return $this->belongsTo(Meja::class, 'meja_id');
}

public function riwayatStatus()
{
    return $this->hasMany(RiwayatStatus::class, 'orders_id');
}

// Update fillable
protected $fillable = [
    'customer_name',
    'table_no',
    'order_date',
    'ordertime',
    'status',
    'total',
    'waiters_id',
    'cashiers_id',
    'meja_id',
    'metode_pembayaran',
    'payment_status',
    'catatan_umum',
];

// Update casts
protected $casts = [
    'order_date' => 'date',
    'total' => 'integer',
];
```

#### OrderDetail Model - Update Fillable
```php
// Update di app/Models/OrderDetail.php
protected $fillable = [
    'order_id',
    'item_id',
    'quantity',
    'harga_satuan',
    'catatan',
    'subtotal',
];

protected $casts = [
    'quantity' => 'integer',
    'harga_satuan' => 'decimal:2',
    'subtotal' => 'decimal:2',
];
```

---

### 3. API Endpoints yang Belum Ada

#### Restaurant Endpoints
```
GET    /api/restaurants          - List semua restoran
GET    /api/restaurants/{id}     - Detail restoran
POST   /api/restaurants          - Create restoran (admin)
PUT    /api/restaurants/{id}     - Update restoran (admin)
DELETE /api/restaurants/{id}     - Delete restoran (admin)
```

#### Meja Endpoints
```
GET    /api/restaurants/{id}/mejas     - List meja per restoran
GET    /api/mejas/{id}                 - Detail meja
POST   /api/mejas                      - Create meja (admin)
PUT    /api/mejas/{id}                 - Update meja (admin)
DELETE /api/mejas/{id}                 - Delete meja (admin)
POST   /api/mejas/{id}/generate-qr     - Generate QR code
```

#### Kategori Endpoints
```
GET    /api/restaurants/{id}/kategoris - List kategori per restoran
GET    /api/kategoris/{id}             - Detail kategori
POST   /api/kategoris                  - Create kategori (admin)
PUT    /api/kategoris/{id}             - Update kategori (admin)
DELETE /api/kategoris/{id}             - Delete kategori (admin)
```

#### Item Endpoints (Tambahan)
```
GET    /api/items                      - List semua items
GET    /api/items/{id}                 - Detail item
GET    /api/kategoris/{id}/items       - List items per kategori
DELETE /api/items/{id}                 - Delete item (soft delete)
```

#### Order Endpoints (Tambahan)
```
GET    /api/orders                     - List orders (filter by status, date)
GET    /api/orders/{id}                - Detail order
PUT    /api/orders/{id}/status         - Update status order
PUT    /api/orders/{id}/payment        - Update payment status
DELETE /api/orders/{id}                - Cancel order
GET    /api/orders/{id}/history        - Riwayat status order
```

#### Public Endpoints (Untuk Customer)
```
GET    /api/public/menu?meja={id}      - Get menu by meja (dengan restoran info)
GET    /api/public/kategoris/{id}/items - Get items by kategori
POST   /api/public/orders              - Create order (tanpa auth)
GET    /api/public/orders/{id}         - Track order status
```

---

### 4. Controllers yang Perlu Dibuat

- `RestaurantController` - CRUD restoran
- `MejaController` - CRUD meja + generate QR
- `KategoriController` - CRUD kategori
- `PublicMenuController` - Endpoint publik untuk customer
- `OrderStatusController` - Update status pesanan
- `RiwayatStatusController` - Tracking history

---

### 5. Update OrderController

#### Perlu Update Logic Create Order
```php
// Harus calculate subtotal per item
// Harus create riwayat_status saat order dibuat
// Harus validate meja_id exists
// Harus handle metode_pembayaran dan set status awal yang benar

public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'meja_id' => 'required|integer|exists:mejas,id',
        'metode_pembayaran' => 'required|in:cash,online',
        'catatan_umum' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.item_id' => 'required|integer|exists:items,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.catatan' => 'nullable|string',
    ]);

    DB::beginTransaction();
    try {
        $total = 0;
        $orderItems = [];

        // Calculate total dan prepare order items
        foreach ($validated['items'] as $orderItem) {
            $item = Item::find($orderItem['item_id']);
            $subtotal = $item->price * $orderItem['quantity'];
            $total += $subtotal;
            
            $orderItems[] = [
                'item_id' => $item->id,
                'quantity' => $orderItem['quantity'],
                'harga_satuan' => $item->price,
                'catatan' => $orderItem['catatan'] ?? null,
                'subtotal' => $subtotal,
            ];
        }

        // Determine initial status
        $initialStatus = $validated['metode_pembayaran'] === 'online' 
            ? 'menunggu_pembayaran' 
            : 'menunggu_konfirmasi_kasir';

        // Get meja info
        $meja = Meja::find($validated['meja_id']);

        // Create order
        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'table_no' => $meja->nomor_meja,
            'meja_id' => $validated['meja_id'],
            'order_date' => now()->toDateString(),
            'ordertime' => now()->toTimeString(),
            'status' => $initialStatus,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'payment_status' => 'belum_lunas',
            'catatan_umum' => $validated['catatan_umum'],
            'total' => $total,
            'waiters_id' => null,
            'cashiers_id' => null,
        ]);

        // Create order details
        foreach ($orderItems as $orderItem) {
            OrderDetail::create([
                'order_id' => $order->id,
                ...$orderItem
            ]);
        }

        // Create riwayat status
        RiwayatStatus::create([
            'orders_id' => $order->id,
            'status' => $initialStatus,
            'timestamp' => now(),
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => $order->load(['orderDetails.item', 'meja'])
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
```

---

### 6. Middleware yang Perlu Dibuat

#### CheckRole Middleware
```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, ...$roles)
{
    if (!$request->user() || !in_array($request->user()->role->name, $roles)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    return $next($request);
}
```

#### Daftarkan di Kernel.php
```php
protected $middlewareAliases = [
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

---

### 7. Seeders yang Perlu Dibuat

#### RoleSeeder
```php
// database/seeders/RoleSeeder.php
Role::create(['name' => 'admin']);
Role::create(['name' => 'kasir']);
Role::create(['name' => 'waiter']);
Role::create(['name' => 'dapur']);
```

#### RestaurantSeeder
```php
// database/seeders/RestaurantSeeder.php
Restaurant::create([
    'nama' => 'Restoran Demo',
    'alamat' => 'Jl. Contoh No. 123, Jakarta'
]);
```

#### KategoriSeeder
```php
// database/seeders/KategoriSeeder.php
Kategori::create(['restoran_id' => 1, 'nama' => 'Makanan', 'urutan' => 1]);
Kategori::create(['restoran_id' => 1, 'nama' => 'Minuman', 'urutan' => 2]);
Kategori::create(['restoran_id' => 1, 'nama' => 'Dessert', 'urutan' => 3]);
```

#### ItemSeeder (10 data seperti yang diminta)
```php
// database/seeders/ItemSeeder.php
$items = [
    ['name' => 'Nasi Goreng', 'price' => 25000, 'kategori_id' => 1],
    ['name' => 'Mie Goreng', 'price' => 20000, 'kategori_id' => 1],
    ['name' => 'Ayam Bakar', 'price' => 35000, 'kategori_id' => 1],
    ['name' => 'Sate Ayam', 'price' => 30000, 'kategori_id' => 1],
    ['name' => 'Gado-Gado', 'price' => 18000, 'kategori_id' => 1],
    ['name' => 'Es Teh', 'price' => 5000, 'kategori_id' => 2],
    ['name' => 'Es Jeruk', 'price' => 7000, 'kategori_id' => 2],
    ['name' => 'Jus Alpukat', 'price' => 12000, 'kategori_id' => 2],
    ['name' => 'Es Campur', 'price' => 15000, 'kategori_id' => 3],
    ['name' => 'Pisang Goreng', 'price' => 10000, 'kategori_id' => 3],
];

foreach ($items as $item) {
    Item::create([
        ...$item,
        'image' => 'items/default.jpg' // Atau generate dummy image
    ]);
}
```

#### MejaSeeder
```php
// database/seeders/MejaSeeder.php
for ($i = 1; $i <= 10; $i++) {
    Meja::create([
        'restoran_id' => 1,
        'nomor_meja' => 'A' . $i,
        'qr_code' => Str::random(32),
    ]);
}
```

---

### 8. QR Code Generation

#### Install Package
```bash
composer require simplesoftwareio/simple-qrcode
```

#### Service untuk Generate QR
```php
// app/Services/QRCodeService.php
namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    public function generateForMeja($meja)
    {
        $url = config('app.frontend_url') . "/menu?meja={$meja->id}&restoran={$meja->restoran_id}";
        
        $filename = "qr-meja-{$meja->id}.png";
        $path = "qrcodes/{$filename}";
        
        QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($url, storage_path("app/public/{$path}"));
        
        return $path;
    }
}
```

---

### 9. Event & Listener untuk Status Updates

#### OrderStatusUpdated Event
```php
// app/Events/OrderStatusUpdated.php
namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new Channel('order.' . $this->order->id);
    }
}
```

#### Listener untuk Create Riwayat Status
```php
// app/Listeners/CreateStatusHistory.php
namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\RiwayatStatus;

class CreateStatusHistory
{
    public function handle(OrderStatusUpdated $event)
    {
        RiwayatStatus::create([
            'orders_id' => $event->order->id,
            'status' => $event->order->status,
            'timestamp' => now(),
        ]);
    }
}
```

---

### 10. Frontend (Vue.js) Components yang Perlu Dibuat

#### Customer Side
- `MenuPage.vue` - Halaman menu dengan filter kategori
- `CartComponent.vue` - Keranjang belanja
- `CheckoutPage.vue` - Halaman checkout
- `OrderStatusPage.vue` - Tracking status pesanan
- `ItemCard.vue` - Card untuk display item
- `CategoryFilter.vue` - Filter kategori

#### Admin Side
- `RestaurantManagement.vue` - CRUD restoran
- `TableManagement.vue` - CRUD meja + generate QR
- `CategoryManagement.vue` - CRUD kategori
- `ItemManagement.vue` - CRUD items
- `QRCodeDisplay.vue` - Display & download QR code

#### Kitchen Side
- `KitchenDashboard.vue` - Dashboard dapur
- `OrderQueue.vue` - Antrian pesanan
- `OrderCard.vue` - Card pesanan dengan action

#### Cashier Side
- `CashierDashboard.vue` - Dashboard kasir
- `PendingPayments.vue` - List pembayaran pending
- `PaymentConfirmation.vue` - Konfirmasi pembayaran

---

### 11. Validation & Business Logic

#### Validasi yang Perlu Ditambahkan
- Cek apakah item masih available (tidak soft deleted)
- Cek apakah meja exists dan belongs to restoran
- Cek apakah kategori belongs to restoran yang sama
- Validate quantity > 0
- Validate price tidak negatif

#### Business Rules
- Order tidak bisa diubah setelah status `diproses`
- Payment status `lunas` hanya bisa diset oleh kasir
- Status flow harus sequential (tidak bisa skip)
- Soft delete item tidak menghapus order history

---

### 12. Testing yang Perlu Dibuat

#### Unit Tests
- Model relationships
- Business logic calculations
- Validation rules

#### Feature Tests
- API endpoints
- Authentication
- Authorization
- Order creation flow
- Status update flow

#### Integration Tests
- End-to-end order flow
- Payment flow
- QR code scanning flow

---

### 13. Configuration Files

#### CORS Configuration
```php
// config/cors.php
'paths' => ['api/*'],
'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:5173')],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

#### Filesystem Configuration
```php
// config/filesystems.php
// Pastikan disk 'public' sudah configured
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

---

### 14. Environment Variables yang Perlu Ditambahkan

```env
# Frontend URL
FRONTEND_URL=http://localhost:5173

# Payment Gateway (Future)
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false

# Broadcasting (Future)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

# Queue (Future)
QUEUE_CONNECTION=database
```

---

### 15. Migration yang Perlu Dijalankan Ulang

Karena ada perubahan struktur, perlu:
```bash
php artisan migrate:fresh --seed
```

**WARNING:** Ini akan menghapus semua data!

---

## Prioritas Pengembangan 🎯

### Phase 1 - Core Features (Minggu 1-2)
1. ✅ Buat semua Models yang kurang
2. ✅ Buat Seeders (Restaurant, Kategori, Item, Meja)
3. ✅ Buat API endpoints untuk Restaurant, Meja, Kategori
4. ✅ Update OrderController dengan logic lengkap
5. ✅ Buat Public API untuk customer (menu, create order)
6. ✅ Generate QR Code untuk meja

### Phase 2 - Frontend Customer (Minggu 3-4)
1. ✅ Setup Vue.js project dengan Vite
2. ✅ Buat halaman Menu (scan QR → menu)
3. ✅ Buat Cart functionality
4. ✅ Buat Checkout page
5. ✅ Buat Order tracking page
6. ✅ Integrate dengan API

### Phase 3 - Admin & Kitchen Dashboard (Minggu 5-6)
1. ✅ Buat Admin dashboard (CRUD semua entity)
2. ✅ Buat Kitchen dashboard (order queue)
3. ✅ Buat Cashier dashboard (payment confirmation)
4. ✅ Implement role-based access control

### Phase 4 - Payment Integration (Minggu 7-8)
1. ✅ Integrate payment gateway (Midtrans/Xendit)
2. ✅ Handle payment callback
3. ✅ Update payment status
4. ✅ Generate invoice/receipt

### Phase 5 - Real-time & Polish (Minggu 9-10)
1. ✅ Implement WebSocket/Pusher untuk real-time updates
2. ✅ Add notifications
3. ✅ Performance optimization
4. ✅ Testing & bug fixes
5. ✅ Documentation

---

## Rekomendasi Tambahan 💡

### 1. Fitur Tambahan yang Berguna
- **Rating & Review** - Customer bisa kasih rating setelah selesai
- **Promo & Discount** - Sistem diskon dan promo
- **Loyalty Points** - Point untuk customer setia
- **Multi-language** - Support bahasa Indonesia & Inggris
- **Dark Mode** - Untuk kenyamanan mata
- **Print Receipt** - Cetak struk untuk kasir
- **Analytics Dashboard** - Laporan penjualan, item terlaris, dll
- **Inventory Management** - Tracking stok bahan

### 2. Performance Optimization
- **Redis Cache** - Cache menu, kategori
- **Image Optimization** - Compress & resize gambar
- **Lazy Loading** - Load gambar on demand
- **Database Indexing** - Index foreign keys
- **Query Optimization** - Eager loading relationships

### 3. Security Enhancements
- **Rate Limiting** - Prevent spam/abuse
- **Input Sanitization** - XSS prevention
- **SQL Injection Prevention** - Parameterized queries
- **HTTPS Only** - Force SSL
- **API Versioning** - `/api/v1/...`

### 4. DevOps & Deployment
- **Docker** - Containerization
- **CI/CD Pipeline** - Automated testing & deployment
- **Monitoring** - Sentry, New Relic
- **Backup Strategy** - Automated database backup
- **Load Balancing** - Untuk scalability

---

## Estimasi Waktu Total ⏱️

- **Backend Development**: 3-4 minggu
- **Frontend Development**: 4-5 minggu
- **Testing & QA**: 2 minggu
- **Deployment & Polish**: 1 minggu

**Total**: ~10-12 minggu untuk MVP (Minimum Viable Product)

---

## Kesimpulan 📝

Project ini sudah memiliki foundation yang baik dengan database schema yang lengkap. Yang perlu dilakukan:

1. **Immediate (Urgent)**:
   - Buat semua Models yang kurang
   - Buat Seeders untuk testing
   - Update OrderController dengan logic lengkap
   - Buat Public API untuk customer

2. **Short Term (1-2 minggu)**:
   - Frontend customer (menu, cart, checkout)
   - QR Code generation
   - Basic admin dashboard

3. **Medium Term (3-4 minggu)**:
   - Kitchen & Cashier dashboard
   - Payment integration
   - Real-time updates

4. **Long Term (Future)**:
   - Advanced features (rating, promo, analytics)
   - Performance optimization
   - Mobile app (React Native/Flutter)

Dengan struktur database yang sudah ada, project ini siap untuk dikembangkan menjadi aplikasi pemesanan makanan yang lengkap dan production-ready! 🚀
