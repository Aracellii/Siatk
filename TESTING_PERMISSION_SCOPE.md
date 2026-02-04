# Testing Permission Scope Bagian

## Setup Test User

Jalankan command berikut di artisan tinker untuk setup test user:

```bash
php artisan tinker
```

### 1. Buat atau Update User dengan Bagian

```php
// Lihat dulu bagian yang tersedia
Bagian::all();

// Misal ada bagian:
// ID 1: IT
// ID 2: Keuangan  
// ID 3: Operasional

// Setup User 1 - Role User, Bagian IT
$user1 = User::find(1); // atau User::where('email', 'user@example.com')->first();
$user1->bagian_id = 1; // Bagian IT
$user1->role = 'user';
$user1->save();
$user1->syncRoles(['user']);

// Setup User 2 - Role Admin, Bagian Keuangan
$user2 = User::find(2); // atau User::where('email', 'admin@example.com')->first();
$user2->bagian_id = 2; // Bagian Keuangan
$user2->role = 'admin';
$user2->save();
$user2->syncRoles(['admin']);

// Setup User 3 - Role Keuangan, Bagian Keuangan
$user3 = User::find(3);
$user3->bagian_id = 2;
$user3->role = 'keuangan';
$user3->save();
$user3->syncRoles(['keuangan']);
```

### 2. Buat Data Test di Berbagai Bagian

```php
// Buat atau update data Gudang (Stok Barang) untuk berbagai bagian

// Stok Barang untuk Bagian IT
Gudang::create([
    'barang_id' => 1,
    'bagian_id' => 1, // IT
    'stok' => 100
]);

Gudang::create([
    'barang_id' => 2,
    'bagian_id' => 1, // IT
    'stok' => 50
]);

// Stok Barang untuk Bagian Keuangan
Gudang::create([
    'barang_id' => 3,
    'bagian_id' => 2, // Keuangan
    'stok' => 75
]);

Gudang::create([
    'barang_id' => 4,
    'bagian_id' => 2, // Keuangan
    'stok' => 30
]);

// Stok Barang untuk Bagian Operasional
Gudang::create([
    'barang_id' => 5,
    'bagian_id' => 3, // Operasional
    'stok' => 200
]);
```

### 3. Verifikasi Permission

```php
// Cek permission user
$user1 = User::find(1);
$user1->getAllPermissions()->pluck('name'); 
// Seharusnya ada 'view_own_bagian_only'

$user2 = User::find(2);
$user2->getAllPermissions()->pluck('name');
// Seharusnya ada 'view_all_bagian'

// Test manual query
$user1 = User::find(1); // User role, bagian IT
auth()->setUser($user1);

// Query dengan scope - seharusnya hanya data bagian IT
$result = App\Traits\HasBagianScope::applyBagianScope(
    Gudang::query(), 
    'bagian_id'
)->get();

// Seharusnya hanya return data dengan bagian_id = 1 (IT)
$result->pluck('bagian_id')->unique(); // [1]
```

## Test via Browser

### Test 1: User Role (Hanya Lihat Bagiannya)

1. **Login** sebagai user dengan role `user` dan `bagian_id = 1` (IT)
2. **Buka Menu** Stok Barang
3. **Expected Result**: 
   - Hanya melihat stok barang dengan bagian = IT
   - Tidak bisa lihat data bagian Keuangan atau Operasional
4. **Buka Menu** Permintaan
5. **Expected Result**:
   - Hanya melihat permintaan yang dibuat sendiri atau dari user bagian IT

### Test 2: Admin Role (Lihat Semua Bagian)

1. **Login** sebagai user dengan role `admin`
2. **Buka Menu** Stok Barang
3. **Expected Result**:
   - Melihat stok barang dari SEMUA bagian
   - Ada data dari IT, Keuangan, Operasional
4. **Buka Menu** Permintaan
5. **Expected Result**:
   - Melihat permintaan dari semua user dan bagian

### Test 3: Super Admin Role (Bypass)

1. **Login** sebagai super admin
2. **Buka Menu** Stok Barang dan Permintaan
3. **Expected Result**:
   - Melihat SEMUA data tanpa filter
   - Bisa akses dan edit semua data

## Debugging

### Jika Data Tidak Terfilter

```bash
# Cek permission cache
php artisan permission:cache-reset

# Cek permission di database
php artisan tinker
```

```php
// Di tinker
$user = User::find(1);
$user->getAllPermissions()->pluck('name');

// Cek apakah user punya bagian_id
$user->bagian_id;
$user->bagian;

// Cek role
$user->roles->pluck('name');

// Cek permission specific
$user->can('view_own_bagian_only'); // true untuk user
$user->can('view_all_bagian'); // true untuk admin/keuangan
```

### Jika User Tidak Bisa Lihat Apapun

```php
// Pastikan user punya permission yang benar
$user = User::find(1);

// Untuk user role
$user->givePermissionTo('view_own_bagian_only');

// Untuk admin role
$user->givePermissionTo('view_all_bagian');

// Atau sync semua permission role
$user->syncRoles(['user']); // atau 'admin', 'keuangan'

// Clear cache
php artisan permission:cache-reset
```

## Expected Behavior Summary

| Role        | Permission           | Behavior                                       |
|-------------|---------------------|------------------------------------------------|
| super_admin | All permissions     | Lihat semua data (bypass filter)               |
| admin       | view_all_bagian     | Lihat data dari semua bagian                   |
| keuangan    | view_all_bagian     | Lihat data dari semua bagian                   |
| user        | view_own_bagian_only | Hanya lihat data dari bagiannya sendiri        |

## Check Permission via Query

```php
php artisan tinker

// Test query manual
use App\Models\Gudang;
use App\Models\User;

$user = User::find(1); // User dengan bagian_id = 1
auth()->setUser($user);

// Gunakan trait static method
use App\Traits\HasBagianScope;
$query = Gudang::query();
$filtered = HasBagianScope::applyBagianScope($query, 'bagian_id')->get();

// Cek hasil
$filtered->count(); // Seharusnya hanya data bagian user
$filtered->pluck('bagian_id')->unique(); // [1] untuk user dengan bagian_id = 1

// Test dengan admin
$admin = User::where('role', 'admin')->first();
auth()->setUser($admin);
$query = Gudang::query();
$filtered = HasBagianScope::applyBagianScope($query, 'bagian_id')->get();
$filtered->count(); // Seharusnya semua data
$filtered->pluck('bagian_id')->unique(); // [1, 2, 3, ...] semua bagian
```

## Common Issues

### Issue 1: "Undefined variable $groupedRecords"
**Solution**: Pastikan di export PDF, data di-group berdasarkan bagian terlebih dahulu

### Issue 2: User masih bisa lihat semua data
**Solution**: 
```bash
php artisan permission:cache-reset
php artisan optimize:clear
```

### Issue 3: Admin hanya lihat bagiannya sendiri
**Solution**: Cek permission admin di database
```php
php artisan tinker
$admin = User::where('role', 'admin')->first();
$admin->getAllPermissions()->pluck('name');
// Harus ada 'view_all_bagian'

// Jika tidak ada
$admin->givePermissionTo('view_all_bagian');
php artisan permission:cache-reset
```

### Issue 4: User tidak punya bagian_id
**Solution**: Set bagian_id untuk user
```php
$user = User::find(1);
$user->bagian_id = 1; // Set ke bagian yang sesuai
$user->save();
```

## Reset dan Re-seed

Jika perlu reset semua:
```bash
# Re-run seeder
php artisan db:seed --class=SimplePermissionSeeder

# Clear all caches
php artisan permission:cache-reset
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear

# Restart server
# Ctrl+C di terminal server, lalu:
php artisan serve
```
