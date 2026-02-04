# Sistem Permission Scope Bagian

## Deskripsi
Sistem permission untuk membatasi akses data berdasarkan unit kerja/bagian. User dengan permission tertentu hanya bisa melihat data dari bagiannya sendiri, sementara yang lain bisa melihat semua data.

## Permission Baru

### 1. `view_own_bagian_only`
**Deskripsi**: User hanya bisa melihat data dari bagiannya sendiri
- User dengan permission ini akan difilter hanya melihat data dengan bagian_id yang sama
- Cocok untuk role: **user**, **staff bagian**

### 2. `view_all_bagian`
**Deskripsi**: User bisa melihat data dari semua bagian
- User dengan permission ini bisa akses semua data tanpa filter
- Cocok untuk role: **admin**, **keuangan**, **manager**

## Konfigurasi Role

Konfigurasi default di `SimplePermissionSeeder`:
```php
// Super Admin - Semua permissions (bypass)
$superAdmin->syncPermissions(array_keys($permissions));

// Admin - Bisa lihat semua bagian
$admin->syncPermissions([
    'view_all_bagian',
    // ... permissions lain
]);

// Keuangan - Bisa lihat semua bagian
$keuangan->syncPermissions([
    'view_all_bagian',
    // ... permissions lain
]);

// User - Hanya lihat bagian sendiri
$user->syncPermissions([
    'view_own_bagian_only',
    // ... permissions lain
]);
```

## Cara Menggunakan

### 1. Setup Trait di Resource

Tambahkan trait `HasBagianScope` di Resource class:

```php
<?php

namespace App\Filament\Resources;

use App\Traits\HasBagianScope;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class YourResource extends Resource
{
    use HasBagianScope;
    
    // ... kode lain
}
```

### 2. Implementasi di Resource Query

#### A. Untuk Model dengan kolom `bagian_id` langsung
Contoh: Gudang, Barang, dll

```php
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    
    // Apply bagian scope
    return static::applyBagianScope($query, 'bagian_id');
}
```

#### B. Untuk Model dengan kolom `user_id` (via relasi user -> bagian)
Contoh: Permintaan, LogAktivitas, dll

```php
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    
    // Apply user scope (via user's bagian)
    return static::applyUserScope($query, 'user_id');
}
```

#### C. Untuk Model dengan nama kolom custom
```php
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    
    // Misal kolom nama: department_id
    return static::applyBagianScope($query, 'department_id');
}
```

### 3. Check Permission di Action/Button

Untuk tombol atau action yang perlu dicek permissionnya:

```php
use App\Traits\HasBagianScope;

// Di dalam Resource/Page class
Tables\Actions\EditAction::make()
    ->visible(function ($record) {
        // Cek apakah user bisa modify record ini
        return static::canModifyRecord($record, 'bagian_id');
    }),

Tables\Actions\DeleteAction::make()
    ->visible(function ($record) {
        return static::canModifyRecord($record, 'bagian_id');
    }),
```

### 4. Filter di Form untuk Create/Edit

Tampilkan hanya data bagian yang relevan saat membuat/edit record:

```php
Forms\Components\Select::make('bagian_id')
    ->label('Bagian')
    ->relationship('bagian', 'nama_bagian')
    ->options(function () {
        $user = auth()->user();
        
        // Super admin atau view_all_bagian bisa pilih semua
        if ($user->hasRole('super_admin') || $user->can('view_all_bagian')) {
            return \App\Models\Bagian::pluck('nama_bagian', 'id');
        }
        
        // view_own_bagian_only hanya bisa pilih bagiannya
        if ($user->can('view_own_bagian_only') && $user->bagian_id) {
            return \App\Models\Bagian::where('id', $user->bagian_id)
                ->pluck('nama_bagian', 'id');
        }
        
        return [];
    })
    ->required(),
```

## Resource yang Sudah Diimplementasikan

### ✅ GudangResource (Stok Barang)
- Model: `Gudang` memiliki `bagian_id`
- Implementasi: `applyBagianScope($query, 'bagian_id')`
- User dengan `view_own_bagian_only` hanya lihat stok bagiannya

### ✅ PermintaanResource
- Model: `Permintaan` memiliki `user_id` (relasi ke User -> Bagian)
- Implementasi: `applyUserScope($query, 'user_id')`
- User dengan `view_own_bagian_only` hanya lihat permintaan dari bagiannya
- Admin role bisa lihat permintaan dari bagiannya
- User biasa hanya lihat permintaannya sendiri

## Contoh Implementasi Resource Lain

### LogAktivitasResource
```php
<?php

namespace App\Filament\Resources;

use App\Traits\HasBagianScope;
use Illuminate\Database\Eloquent\Builder;

class LogAktivitasResource extends Resource
{
    use HasBagianScope;
    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // LogAktivitas punya user_id
        return static::applyUserScope($query, 'user_id');
    }
}
```

### BarangResource (Katalog Barang)
```php
<?php

namespace App\Filament\Resources;

use App\Traits\HasBagianScope;
use Illuminate\Database\Eloquent\Builder;

class BarangResource extends Resource
{
    use HasBagianScope;
    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Jika Barang punya bagian_id
        return static::applyBagianScope($query, 'bagian_id');
        
        // ATAU jika Barang tidak punya bagian_id langsung,
        // bisa akses via gudang:
        // return $query->whereHas('gudangs', function ($q) {
        //     static::applyBagianScope($q, 'bagian_id');
        // });
    }
}
```

## Testing

### 1. Jalankan Seeder
```bash
php artisan db:seed --class=SimplePermissionSeeder
php artisan permission:cache-reset
```

### 2. Test dengan User Role
1. Login sebagai user dengan role `user`
2. Set `bagian_id` pada user tersebut (misal: bagian_id = 1)
3. Buka menu Stok Barang atau Permintaan
4. Seharusnya hanya melihat data dari bagian_id = 1

### 3. Test dengan Admin/Keuangan Role
1. Login sebagai admin atau keuangan
2. Buka menu yang sama
3. Seharusnya bisa melihat data dari semua bagian

## Method di Trait HasBagianScope

### `applyBagianScope(Builder $query, string $bagianColumn)`
- Filter query berdasarkan bagian_id
- Parameter:
  - `$query`: Query builder
  - `$bagianColumn`: Nama kolom bagian_id (default: 'bagian_id')

### `applyUserScope(Builder $query, string $userColumn)`
- Filter query berdasarkan user_id (via relasi user -> bagian)
- Parameter:
  - `$query`: Query builder
  - `$userColumn`: Nama kolom user_id (default: 'user_id')

### `canModifyRecord($record, string $ownerColumn)`
- Check apakah user bisa edit/delete record
- Parameter:
  - `$record`: Model instance
  - `$ownerColumn`: Kolom ownership ('bagian_id' atau 'user_id')
- Return: `bool`

## Troubleshooting

### Data tidak terfilter?
1. Pastikan seeder sudah dijalankan: `php artisan db:seed --class=SimplePermissionSeeder`
2. Clear permission cache: `php artisan permission:cache-reset`
3. Pastikan user memiliki `bagian_id` yang valid

### User tidak bisa lihat data sama sekali?
1. Cek apakah user punya permission `view_own_bagian_only` atau `view_all_bagian`
2. Cek apakah user punya `bagian_id`
3. Cek di database apakah ada data dengan bagian_id yang sama

### Admin masih hanya lihat bagiannya sendiri?
1. Pastikan role admin punya permission `view_all_bagian`
2. Run: `php artisan permission:cache-reset`

## Catatan Penting

1. **Super Admin** selalu bypass semua filter
2. User tanpa `bagian_id` tidak akan bisa melihat data apapun (kecuali super admin)
3. Permission cache harus direset setiap kali ada perubahan permission
4. Trait ini bisa digunakan di Resource manapun yang memiliki relasi ke bagian

## Update User Bagian

Untuk set bagian_id user via tinker:
```php
php artisan tinker

// Set bagian untuk user
$user = User::find(1);
$user->bagian_id = 1; // ID bagian yang sesuai
$user->save();

// Cek bagian user
$user->bagian;
```
