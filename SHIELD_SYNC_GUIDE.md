# 🛡️ Panduan Sinkronisasi Filament Shield

## 🎯 Masalah yang Terjadi
- Permission di seeder tidak sinkron dengan code
- Naming convention berbeda (Indonesia vs English)
- Template variables tidak ter-replace

## ✅ Solusi untuk Mencegah Bug

### **Opsi 1: Gunakan Shield Generator (RECOMMENDED)**

#### 1️⃣ Generate Permission dari Resource
```bash
# Generate semua permission untuk semua resource
php artisan shield:generate --all

# Atau generate untuk resource tertentu
php artisan shield:generate --resource=PermintaanResource
php artisan shield:generate --resource=GudangResource
```

#### 2️⃣ Install Shield (Jika belum)
```bash
# Install semua permission dan role
php artisan shield:install

# Setup super admin
php artisan shield:super-admin
```

#### 3️⃣ Publish Config Shield
```bash
php artisan vendor:publish --tag=filament-shield-config
```

Lalu edit `config/filament-shield.php`:
```php
return [
    'shield_resource' => [
        'should_register_navigation' => true,
        'slug' => 'shield/roles',
        'navigation_sort' => -1,
        'navigation_badge' => true,
        'navigation_group' => true,
        'is_globally_searchable' => false,
        'show_model_path' => true,
    ],

    'auth_provider_model' => [
        'fqcn' => 'App\\Models\\User',
    ],

    'super_admin' => [
        'enabled' => true,
        'name' => 'super_admin',
        'define_via_gate' => false,
    ],

    'permission_prefixes' => [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ],

        'page' => 'page',
        'widget' => 'widget',
    ],

    'entities' => [
        'pages' => true,
        'widgets' => true,
        'resources' => true,
        'custom_permissions' => false,
    ],

    'generator' => [
        'option' => 'policies_and_permissions',
    ],
];
```

---

### **Opsi 2: Custom Seeder yang Sinkron dengan Shield**

Buat seeder baru yang generate permission sesuai Shield convention:

```bash
php artisan make:seeder ShieldSyncSeeder
```

Edit `database/seeders/ShieldSyncSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSyncSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define resources
        $resources = [
            'gudang' => 'Stok Barang',
            'barang' => 'Katalog Barang',
            'permintaan' => 'Permintaan',
            'user' => 'Manajemen User',
            'log_aktivitas' => 'Log Aktivitas',
        ];

        // Define standard CRUD permissions
        $crudPermissions = [
            'view' => 'Lihat',
            'view_any' => 'Lihat Daftar',
            'create' => 'Buat',
            'update' => 'Edit',
            'delete' => 'Hapus',
            'delete_any' => 'Hapus Massal',
            'restore' => 'Restore',
            'restore_any' => 'Restore Massal',
            'replicate' => 'Duplikat',
            'reorder' => 'Atur Ulang',
            'force_delete' => 'Hapus Permanen',
            'force_delete_any' => 'Hapus Permanen Massal',
        ];

        // Generate permissions for each resource
        foreach ($resources as $resource => $label) {
            foreach ($crudPermissions as $action => $actionLabel) {
                Permission::firstOrCreate([
                    'name' => $action . '_' . $resource,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Custom permissions (menu access, etc)
        $customPermissions = [
            'access_dashboard' => 'Akses Dashboard',
            'akses_stok' => 'Akses Menu Stok Barang',
            'akses_katalog' => 'Akses Menu Katalog Barang',
            'akses_permintaan' => 'Akses Menu Permintaan',
            'akses_log' => 'Akses Menu Log Aktivitas',
            'akses_managemen_user' => 'Akses Menu Manajemen User',
            'manage_roles' => 'Kelola Roles',
            'export_stok_barang' => 'Export Stok',
            'export_permintaan' => 'Export Permintaan',
            'approve_permintaan' => 'Approve Permintaan',
        ];

        foreach ($customPermissions as $name => $description) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('✅ Permissions synced with Shield!');
    }
}
```

Jalankan:
```bash
php artisan db:seed --class=ShieldSyncSeeder
```

---

### **Opsi 3: Override Policy dengan Custom Logic**

Edit file policy, misalnya `app/Policies/GudangPolicy.php`:

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Gudang;
use Illuminate\Auth\Access\HandlesAuthorization;

class GudangPolicy
{
    use HandlesAuthorization;

    // Gunakan permission Shield standard
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_gudang') 
            || $user->hasPermissionTo('akses_stok');
    }

    public function view(User $user, Gudang $gudang): bool
    {
        return $user->hasPermissionTo('view_gudang') 
            || $user->hasPermissionTo('view_stok_barang');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_gudang') 
            || $user->hasPermissionTo('manage_stok_barang');
    }

    public function update(User $user, Gudang $gudang): bool
    {
        return $user->hasPermissionTo('update_gudang') 
            || $user->hasPermissionTo('manage_stok_barang');
    }

    public function delete(User $user, Gudang $gudang): bool
    {
        return $user->hasPermissionTo('delete_gudang') 
            || $user->hasPermissionTo('manage_stok_barang');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_any_gudang') 
            || $user->hasPermissionTo('manage_stok_barang');
    }

    // ... methods lainnya
}
```

---

### **Opsi 4: Disable Shield Policy Generator**

Jika ingin pakai custom permission tanpa Shield convention:

Edit `config/filament-shield.php`:
```php
'generator' => [
    'option' => 'permissions', // Hanya generate permission, tidak policy
],
```

Lalu buat policy manual yang sesuai dengan permission custom Anda.

---

## 🔄 Workflow yang Benar

### Saat Buat Resource Baru:

1. **Buat Resource**
   ```bash
   php artisan make:filament-resource NamaResource
   ```

2. **Generate Permission & Policy**
   ```bash
   php artisan shield:generate --resource=NamaResource
   ```

3. **Atau Generate Manual di Seeder**
   - Tambahkan permission ke `SimplePermissionSeeder.php`
   - Jalankan `php artisan db:seed --class=SimplePermissionSeeder`

4. **Assign ke Role**
   - Via UI di `/app/shield/roles`
   - Atau via seeder

5. **Clear Cache**
   ```bash
   php artisan permission:cache-reset
   php artisan optimize:clear
   ```

---

## 🛠️ Tools Untuk Debugging

### 1. Check Permission yang Ada
```bash
php artisan tinker
```
```php
// List semua permission
\Spatie\Permission\Models\Permission::pluck('name');

// Check permission user
$user = \App\Models\User::find(1);
$user->getAllPermissions()->pluck('name');

// Check permission role
\Spatie\Permission\Models\Role::findByName('admin')->permissions->pluck('name');
```

### 2. Check Missing Permission
Buat command untuk check:

```bash
php artisan make:command CheckMissingPermissions
```

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class CheckMissingPermissions extends Command
{
    protected $signature = 'permission:check';
    protected $description = 'Check for missing permissions in code';

    public function handle()
    {
        $files = [
            app_path('Filament/Resources'),
            app_path('Policies'),
        ];

        $usedPermissions = [];
        
        foreach ($files as $dir) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir)
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $content = file_get_contents($file->getPathname());
                    
                    // Find hasPermissionTo calls
                    preg_match_all(
                        "/hasPermissionTo\(['\"](.+?)['\"]\)/",
                        $content,
                        $matches
                    );
                    
                    $usedPermissions = array_merge($usedPermissions, $matches[1]);
                }
            }
        }

        $usedPermissions = array_unique($usedPermissions);
        $existingPermissions = Permission::pluck('name')->toArray();
        
        $missing = array_diff($usedPermissions, $existingPermissions);

        if (empty($missing)) {
            $this->info('✅ All permissions exist!');
        } else {
            $this->error('❌ Missing permissions:');
            foreach ($missing as $perm) {
                $this->line("  - $perm");
            }
        }
    }
}
```

Jalankan:
```bash
php artisan permission:check
```

---

## 📋 Checklist Sebelum Deploy

- [ ] Semua permission ada di database
- [ ] Policy menggunakan permission yang benar
- [ ] Resource menggunakan permission yang benar
- [ ] Role sudah di-assign permission yang sesuai
- [ ] Cache sudah di-clear
- [ ] Test login dengan berbagai role
- [ ] No template variables ({{ }}) tersisa

---

## 🎯 Best Practice

1. **Pilih 1 Convention dan Konsisten**
   - Shield standard: `view_any_gudang`, `create_gudang`
   - Custom: `akses_stok`, `manage_stok_barang`
   - **Jangan campur!**

2. **Gunakan Shield Generator**
   - Untuk resource baru
   - Untuk update permission

3. **Custom Permission untuk Menu**
   - `akses_*` untuk menu access
   - `manage_*` untuk CRUD
   - `approve_*` untuk approval
   - `export_*` untuk export

4. **Version Control Seeder**
   - Commit seeder changes
   - Document permission changes

5. **Test Setelah Changes**
   ```bash
   php artisan permission:check
   php artisan permission:cache-reset
   ```

---

## 🚀 Quick Fix untuk Project Ini

Jalankan command ini untuk fix semua:

```bash
# 1. Clear cache
php artisan permission:cache-reset
php artisan optimize:clear

# 2. Re-seed permission
php artisan db:seed --class=SimplePermissionSeeder

# 3. Check missing
php artisan permission:check

# 4. Restart server
# Ctrl+C lalu
php artisan serve
```

Refresh browser, seharusnya sudah tidak ada error! ✅
