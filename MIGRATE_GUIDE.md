# 📚 Panduan Migrasi Database

## 🚀 Cara Migrate Database

### 1️⃣ Fresh Migration (Database Baru/Reset Total)

```bash
php artisan migrate:fresh --seed
```

**⚠️ WARNING**: Perintah ini akan **menghapus semua data** dan membuat ulang database dari awal!

### 2️⃣ Migration Pertama Kali (Database Kosong)

```bash
php artisan migrate --seed
```

### 3️⃣ Migration Tanpa Seed (Hanya Struktur)

```bash
php artisan migrate
```

Lalu jalankan seeder manual:

```bash
php artisan db:seed
```

---

## 📋 Data Yang Akan Di-Seed

### 1. **Bagian/Unit Kerja** (6 bagian)
- Tata Usaha
- Survei dan Pemetaan
- Penetapan Hak dan Pendaftaran
- Penataan dan Pemberdayaan
- Pengadaan Tanah dan Pengembangan
- Pengendalian dan Penanganan Sengketa

### 2. **Roles & Permissions**
- **super_admin**: Full akses semua fitur
- **admin**: Kelola stok, katalog, permintaan (per bagian)
- **keuangan**: Full akses + approve permintaan + kelola roles
- **user**: Buat permintaan, lihat stok (per bagian)

### 3. **Users** (13 users)

| Email | Password | Role | Bagian | Deskripsi |
|-------|----------|------|--------|-----------|
| `admin@gmail.com` | `12345678` | keuangan | Tata Usaha | Admin Keuangan (Full Access) |
| `gudangTU@gmail.com` | `12345678` | admin | Tata Usaha | Admin Gudang TU |
| `userTU@gmail.com` | `12345678` | user | Tata Usaha | Staf TU |
| `gudangSP@gmail.com` | `12345678` | admin | Survei & Pemetaan | Admin Gudang SP |
| `userSP@gmail.com` | `12345678` | user | Survei & Pemetaan | Staf SP |
| `gudangPHP@gmail.com` | `12345678` | admin | Penetapan Hak | Admin Gudang PHP |
| `userPHP@gmail.com` | `12345678` | user | Penetapan Hak | Staf PHP |
| `gudangPP@gmail.com` | `12345678` | admin | Penataan | Admin Gudang PP |
| `userPP@gmail.com` | `12345678` | user | Penataan | Staf PP |
| `gudangPTP@gmail.com` | `12345678` | admin | Pengadaan Tanah | Admin Gudang PTP |
| `userPTP@gmail.com` | `12345678` | user | Pengadaan Tanah | Staf PTP |
| `gudangPPS@gmail.com` | `12345678` | admin | Pengendalian | Admin Gudang PPS |
| `userPPS@gmail.com` | `12345678` | user | Pengendalian | Staf PPS |

### 4. **Barang** (8 barang)
- B001: Pensil
- B002: Buku
- B003: Kertas A4
- B004: Galon
- B005: Dispenser
- B006: Binder
- B007: Kabel
- B008: Map

### 5. **Gudang/Stok** (48 records)
Setiap bagian (6) memiliki stok untuk setiap barang (8):
- B001 (Pensil): 5 unit per bagian
- B002 (Buku): 15 unit per bagian
- B003-B008: 30 unit per bagian

### 6. **Permintaan** (5 permintaan + detail)
Sample permintaan dengan berbagai status:
- **Approved**: 2 permintaan yang sudah disetujui
- **Pending**: 2 permintaan menunggu approval
- **Rejected**: 1 permintaan yang ditolak
- Total **10 detail permintaan** dan **6 detail terverifikasi**

### 7. **Log Aktivitas** (7 logs)
Sample log untuk demo aktivitas gudang:
- **Barang Masuk**: 3 logs (penambahan stok)
- **Barang Keluar**: 2 logs (pengambilan barang)
- **Penyesuaian**: 2 logs (koreksi stok)

---

## 🔄 Urutan Seeding

Seeder akan dijalankan dalam urutan berikut (otomatis via `DatabaseSeeder`):

1. **BagianSeeder** - Setup 6 bagian/unit kerja
2. **SimplePermissionSeeder** - Setup roles & permissions
3. **UserSeeder** - Buat 13 user dengan role masing-masing
4. **BarangSeeder** - Buat 8 master barang
5. **GudangSeeder** - Buat 48 stok barang per bagian
6. **PermintaanSeeder** - Buat 5 sample permintaan + detail
7. **LogAktivitasSeeder** - Buat 7 sample log aktivitas

---

## 🛠️ Troubleshooting

### Error: "Table already exists"

```bash
# Reset total database
php artisan migrate:fresh --seed
```

### Error: "Permission already exists"

```bash
# Clear cache permission
php artisan permission:cache-reset
php artisan cache:clear
```

### Error: "Class not found"

```bash
# Regenerate autoload
composer dump-autoload
```

### Hanya Seed Ulang Tanpa Drop Table

```bash
# Truncate semua data lalu seed ulang
php artisan migrate:refresh --seed
```

---

## 📝 Catatan Penting

1. **Password Default**: Semua user menggunakan password `12345678`
2. **Super Admin**: Belum ada di seeder, bisa dibuat manual atau tambahkan di `UserSeeder`
3. **Role Keuangan**: Sekarang sudah punya akses ke Roles menu dengan permission `manage_roles`
4. **Permission System**: Menggunakan Spatie Permission + Filament Shield
5. **Bagian Scope**: 
   - `lihat_semua_bagian`: Keuangan, Admin (bisa lihat semua)
   - `lihat_bagian_sendiri`: User (hanya lihat bagiannya)

---

## 🎯 Login Setelah Migrate

**Untuk Testing:**
- **Keuangan (Full Access)**: `admin@gmail.com` / `12345678`
- **Admin Gudang**: `gudangTU@gmail.com` / `12345678`
- **User/Staf**: `userTU@gmail.com` / `12345678`

---

## 📦 File Seeder

Semua seeder ada di folder `database/seeders/`:
- `DatabaseSeeder.php` - Orchestrator utama
- `BagianSeeder.php` - Data bagian (6 bagian)
- `SimplePermissionSeeder.php` - Roles & permissions (4 roles, 40 permissions)
- `UserSeeder.php` - Data user (13 users)
- `BarangSeeder.php` - Data barang (8 barang)
- `GudangSeeder.php` - Data stok gudang (48 records)
- `PermintaanSeeder.php` - Sample permintaan (5 permintaan + detail)
- `LogAktivitasSeeder.php` - Sample log aktivitas (7 logs)

---

**Happy Coding! 🚀**
