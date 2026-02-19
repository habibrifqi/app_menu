# Laravel + Vue.js + Inertia.js untuk Aplikasi Pemesanan Makanan

## Apakah Bisa Menggunakan Laravel Inertia + Vue?

**JAWABAN: YA, SANGAT BISA DAN SANGAT DIREKOMENDASIKAN! ✅**

Inertia.js adalah pilihan yang **SEMPURNA** untuk project aplikasi pemesanan makanan Anda karena:

### Keuntungan Menggunakan Inertia.js

1. **Monolithic Architecture** - Satu codebase, tidak perlu pisah backend & frontend
2. **No API Required** - Tidak perlu buat REST API terpisah (kecuali untuk mobile app)
3. **Server-Side Routing** - Routing tetap di Laravel (familiar & powerful)
4. **Automatic CSRF Protection** - Built-in security
5. **Shared Data** - Easy sharing data antar pages
6. **Code Splitting** - Automatic lazy loading components
7. **SSR Support** - Server-side rendering untuk SEO (optional)

---

## Perbandingan: API-Based vs Inertia.js

### Arsitektur Saat Ini (API-Based)
```
Frontend (Vue SPA)  ←→  REST API (Laravel)  ←→  Database
     Port 5173              Port 8000
```

**Kekurangan:**
- Harus maintain 2 codebase terpisah
- CORS configuration
- API versioning complexity
- Duplicate validation (frontend & backend)
- Authentication token management

### Arsitektur dengan Inertia.js
```
Browser  ←→  Laravel (Backend + Frontend)  ←→  Database
                    Port 8000
```

**Keuntungan:**
- Single codebase
- No CORS issues
- No API versioning needed
- Single source of truth untuk validation
- Session-based auth (simpler)

---

## Kapan Pakai API-Based vs Inertia?

### Gunakan **API-Based** jika:
- ❌ Butuh mobile app (iOS/Android native)
- ❌ Butuh multiple frontend (web, mobile, desktop)
- ❌ Butuh third-party integration yang consume API
- ❌ Butuh microservices architecture
- ❌ Team terpisah (backend & frontend developers)

### Gunakan **Inertia.js** jika:
- ✅ Web application only (atau PWA)
- ✅ Single team (fullstack developers)
- ✅ Rapid development
- ✅ Monolithic architecture
- ✅ Ingin simplicity & productivity

---

## Untuk Project Anda: Rekomendasi

### Skenario 1: Web App Only (Customer + Admin)
**REKOMENDASI: Gunakan Inertia.js** ✅

Alasan:
- Customer scan QR → web app (bukan native app)
- Admin dashboard → web app
- Kitchen dashboard → web app
- Cashier dashboard → web app
- Semua bisa di-handle dengan Inertia.js

### Skenario 2: Web App + Future Mobile App
**REKOMENDASI: Hybrid Approach** ⚡

```
Inertia.js (untuk web)  +  API (untuk mobile)
```

Laravel bisa serve both:
- Inertia routes untuk web app
- API routes untuk mobile app (future)

---

## Struktur Project dengan Inertia.js

### Directory Structure
```
aplikasi-menu/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── MenuController.php          (Inertia controller)
│   │   │   ├── OrderController.php         (Inertia controller)
│   │   │   ├── Admin/
│   │   │   │   ├── RestaurantController.php
│   │   │   │   ├── ItemController.php
│   │   │   │   └── CategoryController.php
│   │   │   ├── Kitchen/
│   │   │   │   └── OrderController.php
│   │   │   └── Cashier/
│   │   │       └── PaymentController.php
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php
│   └── Models/
├── resources/
│   ├── js/
│   │   ├── Pages/                          (Vue pages)
│   │   │   ├── Customer/
│   │   │   │   ├── Menu.vue
│   │   │   │   ├── Cart.vue
│   │   │   │   ├── Checkout.vue
│   │   │   │   └── OrderStatus.vue
│   │   │   ├── Admin/
│   │   │   │   ├── Dashboard.vue
│   │   │   │   ├── Restaurants/
│   │   │   │   │   ├── Index.vue
│   │   │   │   │   ├── Create.vue
│   │   │   │   │   └── Edit.vue
│   │   │   │   ├── Items/
│   │   │   │   ├── Categories/
│   │   │   │   └── Tables/
│   │   │   ├── Kitchen/
│   │   │   │   └── Dashboard.vue
│   │   │   └── Cashier/
│   │   │       └── Dashboard.vue
│   │   ├── Components/                     (Reusable components)
│   │   │   ├── ItemCard.vue
│   │   │   ├── CartItem.vue
│   │   │   ├── OrderCard.vue
│   │   │   └── Layout/
│   │   │       ├── AppLayout.vue
│   │   │       ├── AdminLayout.vue
│   │   │       └── GuestLayout.vue
│   │   ├── Composables/                    (Vue composables)
│   │   │   ├── useCart.js
│   │   │   ├── useOrder.js
│   │   │   └── useNotification.js
│   │   └── app.js                          (Entry point)
│   └── views/
│       └── app.blade.php                   (Root template)
├── routes/
│   ├── web.php                             (Inertia routes)
│   └── api.php                             (Optional: untuk mobile)
└── package.json
```

---

## Installation & Setup

### 1. Install Inertia.js Server-Side (Laravel)

```bash
# Install Inertia Laravel adapter
composer require inertiajs/inertia-laravel

# Publish middleware
php artisan inertia:middleware
```

### 2. Register Middleware

```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... existing middleware
        \App\Http\Middleware\HandleInertiaRequests::class,
    ],
];
```

### 3. Setup Root Template

```blade
<!-- resources/views/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Aplikasi Menu') }}</title>
    
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
```

### 4. Install Inertia.js Client-Side (Vue)

```bash
# Install Vue 3 & Inertia Vue adapter
npm install @inertiajs/vue3 vue@^3

# Install Vite plugin
npm install @vitejs/plugin-vue

# Install additional packages
npm install @headlessui/vue @heroicons/vue
npm install axios
npm install pinia  # State management (optional)
```

### 5. Setup Vite Configuration

```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
```

### 6. Setup Vue App

```javascript
// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

createInertiaApp({
    title: (title) => `${title} - Aplikasi Menu`,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')
    ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
})
```

---

## Contoh Implementation

### 1. Controller (Laravel)

```php
// app/Http/Controllers/MenuController.php
namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Restaurant;
use App\Models\Kategori;
use App\Models\Item;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $mejaId = $request->query('meja');
        $restoranId = $request->query('restoran');
        
        $meja = Meja::with('restaurant')->findOrFail($mejaId);
        $kategoris = Kategori::where('restoran_id', $restoranId)
            ->with(['items' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->orderBy('urutan')
            ->get();
        
        return Inertia::render('Customer/Menu', [
            'meja' => $meja,
            'restaurant' => $meja->restaurant,
            'kategoris' => $kategoris,
        ]);
    }
}
```

### 2. Vue Page Component

```vue
<!-- resources/js/Pages/Customer/Menu.vue -->
<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import ItemCard from '@/Components/ItemCard.vue'
import { useCart } from '@/Composables/useCart'

const props = defineProps({
    meja: Object,
    restaurant: Object,
    kategoris: Array,
})

const { cart, addToCart } = useCart()
const selectedKategori = ref(null)

const filteredItems = computed(() => {
    if (!selectedKategori.value) {
        return props.kategoris.flatMap(k => k.items)
    }
    return props.kategoris
        .find(k => k.id === selectedKategori.value)
        ?.items || []
})
</script>

<template>
    <Head :title="`Menu - ${restaurant.nama}`" />
    
    <AppLayout>
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <h1 class="text-3xl font-bold">{{ restaurant.nama }}</h1>
                <p class="text-gray-600">Meja: {{ meja.nomor_meja }}</p>
            </div>
        </div>
        
        <!-- Category Filter -->
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex gap-2 overflow-x-auto">
                <button
                    @click="selectedKategori = null"
                    :class="[
                        'px-4 py-2 rounded-lg whitespace-nowrap',
                        !selectedKategori 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-200'
                    ]"
                >
                    Semua
                </button>
                <button
                    v-for="kategori in kategoris"
                    :key="kategori.id"
                    @click="selectedKategori = kategori.id"
                    :class="[
                        'px-4 py-2 rounded-lg whitespace-nowrap',
                        selectedKategori === kategori.id 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-200'
                    ]"
                >
                    {{ kategori.nama }}
                </button>
            </div>
        </div>
        
        <!-- Items Grid -->
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <ItemCard
                    v-for="item in filteredItems"
                    :key="item.id"
                    :item="item"
                    @add-to-cart="addToCart"
                />
            </div>
        </div>
        
        <!-- Floating Cart Button -->
        <div class="fixed bottom-4 right-4">
            <button
                @click="$inertia.visit('/cart')"
                class="bg-blue-600 text-white px-6 py-3 rounded-full shadow-lg"
            >
                Keranjang ({{ cart.length }})
            </button>
        </div>
    </AppLayout>
</template>
```

### 3. Routes (Laravel)

```php
// routes/web.php
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\RestaurantController;

// Customer Routes
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('restaurants', RestaurantController::class);
    Route::resource('items', ItemController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tables', TableController::class);
});

// Kitchen Routes
Route::middleware(['auth', 'role:dapur'])->prefix('kitchen')->group(function () {
    Route::get('/dashboard', [KitchenController::class, 'index']);
    Route::post('/orders/{order}/update-status', [KitchenController::class, 'updateStatus']);
});

// Cashier Routes
Route::middleware(['auth', 'role:kasir'])->prefix('cashier')->group(function () {
    Route::get('/dashboard', [CashierController::class, 'index']);
    Route::post('/orders/{order}/confirm-payment', [CashierController::class, 'confirmPayment']);
});
```

---

## Hybrid Approach: Inertia + API

Jika Anda ingin support web app (Inertia) DAN mobile app (API) di masa depan:

### Setup Dual Routes

```php
// routes/web.php (Inertia untuk web)
Route::get('/menu', [MenuController::class, 'index']);

// routes/api.php (API untuk mobile)
Route::get('/menu', [Api\MenuController::class, 'index']);
```

### Shared Logic dengan Service Layer

```php
// app/Services/OrderService.php
namespace App\Services;

class OrderService
{
    public function createOrder($data)
    {
        // Business logic di sini
        // Bisa dipanggil dari Inertia controller atau API controller
    }
}

// Inertia Controller
class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}
    
    public function store(Request $request)
    {
        $order = $this->orderService->createOrder($request->validated());
        return redirect()->route('order.show', $order);
    }
}

// API Controller
class ApiOrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}
    
    public function store(Request $request)
    {
        $order = $this->orderService->createOrder($request->validated());
        return response()->json(['data' => $order], 201);
    }
}
```

---

## Migration dari API-Based ke Inertia

### Step-by-Step Migration

1. **Install Inertia** (sudah dijelaskan di atas)

2. **Convert API Controllers ke Inertia Controllers**
```php
// Before (API)
public function index()
{
    $items = Item::all();
    return response()->json(['data' => $items]);
}

// After (Inertia)
public function index()
{
    $items = Item::all();
    return Inertia::render('Items/Index', [
        'items' => $items
    ]);
}
```

3. **Convert Vue SPA ke Inertia Pages**
```javascript
// Before (SPA with axios)
async function fetchItems() {
    const response = await axios.get('/api/items')
    items.value = response.data.data
}

// After (Inertia)
// Data sudah tersedia di props
const props = defineProps({
    items: Array
})
```

4. **Update Navigation**
```vue
<!-- Before (SPA) -->
<router-link to="/menu">Menu</router-link>

<!-- After (Inertia) -->
<Link href="/menu">Menu</Link>
```

5. **Keep API Routes** (optional untuk mobile)
```php
// routes/api.php - keep untuk mobile app future
Route::get('/items', [Api\ItemController::class, 'index']);
```

---

## State Management

### Dengan Inertia (Recommended)

**Tidak perlu Pinia/Vuex yang kompleks!**

Gunakan:
1. **Shared Data** - Via `HandleInertiaRequests` middleware
2. **Vue Composables** - Untuk logic reusable
3. **Local Storage** - Untuk cart (persist)

```javascript
// resources/js/Composables/useCart.js
import { ref, computed } from 'vue'

const cart = ref(JSON.parse(localStorage.getItem('cart') || '[]'))

export function useCart() {
    const addToCart = (item) => {
        cart.value.push(item)
        localStorage.setItem('cart', JSON.stringify(cart.value))
    }
    
    const total = computed(() => {
        return cart.value.reduce((sum, item) => sum + item.subtotal, 0)
    })
    
    return { cart, addToCart, total }
}
```

---

## Authentication

### Dengan Inertia (Session-Based)

```php
// Login Controller
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
    
    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ]);
}
```

```vue
<!-- Login Page -->
<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
})

const submit = () => {
    form.post('/login')
}
</script>

<template>
    <form @submit.prevent="submit">
        <input v-model="form.email" type="email" />
        <input v-model="form.password" type="password" />
        <button type="submit" :disabled="form.processing">Login</button>
    </form>
</template>
```

---

## Real-time Updates dengan Inertia

### Option 1: Polling

```vue
<script setup>
import { onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

let interval

onMounted(() => {
    interval = setInterval(() => {
        router.reload({ only: ['orders'] })
    }, 5000) // Poll every 5 seconds
})

onUnmounted(() => {
    clearInterval(interval)
})
</script>
```

### Option 2: Laravel Echo + Pusher

```javascript
// resources/js/app.js
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
})

// In component
Echo.channel(`order.${orderId}`)
    .listen('OrderStatusUpdated', (e) => {
        router.reload({ only: ['order'] })
    })
```

---

## Kesimpulan & Rekomendasi

### ✅ REKOMENDASI FINAL

**Gunakan Laravel + Inertia.js + Vue.js untuk project ini!**

**Alasan:**
1. ✅ **Simplicity** - Satu codebase, lebih mudah maintain
2. ✅ **Productivity** - Development lebih cepat
3. ✅ **Security** - Built-in CSRF, session-based auth
4. ✅ **SEO-Friendly** - Server-side rendering (optional)
5. ✅ **Modern Stack** - Vue 3 Composition API, Vite, Tailwind CSS
6. ✅ **Scalable** - Bisa tambah API routes untuk mobile app nanti

### 📋 Action Plan

**Phase 1: Setup Inertia** (1-2 hari)
- Install Inertia Laravel & Vue
- Setup Vite configuration
- Create base layouts

**Phase 2: Customer Pages** (1 minggu)
- Menu page dengan QR scan
- Cart functionality
- Checkout flow
- Order tracking

**Phase 3: Admin Dashboard** (1 minggu)
- Restaurant management
- Item management
- Category management
- Table management + QR generation

**Phase 4: Kitchen & Cashier** (3-4 hari)
- Kitchen dashboard
- Cashier dashboard
- Status updates

**Phase 5: Polish** (3-4 hari)
- Real-time updates
- Notifications
- Testing
- Deployment

**Total Estimasi: 3-4 minggu untuk MVP**

---

## Tunggu Perintah Eksekusi

Saya sudah jelaskan lengkap tentang Laravel + Inertia.js + Vue.js.

**Apakah Anda ingin saya eksekusi setup Inertia.js sekarang?**

Jika ya, saya akan:
1. Install Inertia.js dependencies
2. Setup configuration files
3. Create base layouts
4. Convert existing controllers
5. Create example pages

**Silakan beri perintah untuk melanjutkan! 🚀**
