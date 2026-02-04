# 🛡️ Cara Edit Permissions via Website

## ✅ Setup Complete!

Super Admin sekarang bisa mengelola roles & permissions melalui website tanpa perlu coding!

---

## 📍 Cara Akses

### 1. **Login sebagai Super Admin**
```
Email: admin@gmail.com
Role: superadmin
```

### 2. **Buka Menu Roles & Permissions**
```
Sidebar → Pengaturan → Roles & Permissions
```

Atau langsung ke URL:
```
http://localhost:8000/app/shield/roles
```

---

## 🎯 Fitur yang Tersedia

### **List Roles**
- Lihat semua roles (super_admin, admin, keuangan, user)
- Badge menunjukkan jumlah permissions per role
- Filter dan search roles

### **Create Role** (Opsional)
- Tambah role baru jika diperlukan
- Pilih permissions yang diinginkan

### **Edit Role** 
- **Ini yang paling penting!**
- Edit permissions untuk role tertentu
- Toggle permissions on/off
- Permissions dikelompokkan per fitur

### **Delete Role**
- Hapus role yang tidak diperlukan
- Protected: tidak bisa hapus role yang sedang digunakan

---

## 📝 Cara Edit Permissions

### Step-by-Step:

#### 1. **Buka Roles & Permissions**
```
Sidebar → Pengaturan → Roles & Permissions
```

#### 2. **Pilih Role yang Mau Diedit**
Klik icon **pencil/edit** di samping role, misalnya:
- Edit role `admin` untuk mengubah permission admin
- Edit role `keuangan` untuk mengubah permission keuangan
- Edit role `user` untuk mengubah permission user biasa

#### 3. **Toggle Permissions**
Anda akan melihat form dengan permissions dikelompokkan:

**Dashboard**
- ☑ access_dashboard

**Stok Barang**
- ☑ access_stok_barang
- ☑ view_stok_barang
- ☑ manage_stok_barang
- ☑ export_stok_barang

**Katalog Barang**
- ☑ access_katalog_barang
- ☑ view_katalog_barang
- ☑ manage_katalog_barang
- ☐ import_katalog_barang ← Uncheck jika tidak boleh import
- ☑ export_katalog_barang

**Permintaan**
- ☑ access_permintaan
- ☑ view_permintaan
- ☑ create_permintaan
- ☑ manage_permintaan
- ☑ approve_permintaan ← Check jika boleh approve
- ☑ export_permintaan

Dan seterusnya...

#### 4. **Select All Toggle**
Ada toggle "Select All" di atas untuk:
- ✅ Check semua permissions
- ❌ Uncheck semua permissions

#### 5. **Save Changes**
Klik **Save** / **Update** untuk menyimpan perubahan.

---

## 💡 Contoh Use Cases

### **Scenario 1: Admin Tidak Boleh Import Barang**

1. Buka Roles & Permissions
2. Edit role `admin`
3. Scroll ke section "Katalog Barang"
4. Uncheck `import_katalog_barang`
5. Save

✅ Admin tidak bisa import barang lagi!

---

### **Scenario 2: User Biasa Boleh Export Permintaan**

1. Buka Roles & Permissions
2. Edit role `user`
3. Scroll ke section "Permintaan"
4. Check `export_permintaan`
5. Save

✅ User biasa sekarang bisa export permintaan!

---

### **Scenario 3: Keuangan Tidak Boleh Hapus Log**

1. Buka Roles & Permissions
2. Edit role `keuangan`
3. Scroll ke section "Log Aktivitas"
4. Uncheck `clear_log_aktivitas`
5. Save

✅ Keuangan tidak bisa hapus log!

---

## 🎨 Permission Grouping

Permissions dikelompokkan berdasarkan fitur:

```
📊 Dashboard
  └─ access_dashboard

📦 Stok Barang
  ├─ access_stok_barang
  ├─ view_stok_barang
  ├─ manage_stok_barang
  └─ export_stok_barang

📚 Katalog Barang
  ├─ access_katalog_barang
  ├─ view_katalog_barang
  ├─ manage_katalog_barang
  ├─ import_katalog_barang
  └─ export_katalog_barang

📋 Permintaan
  ├─ access_permintaan
  ├─ view_permintaan
  ├─ create_permintaan
  ├─ manage_permintaan
  ├─ approve_permintaan
  └─ export_permintaan

📜 Log Aktivitas
  ├─ access_log_aktivitas
  ├─ view_log_aktivitas
  ├─ export_log_aktivitas
  └─ clear_log_aktivitas

👥 Manajemen User
  ├─ access_manajemen_user
  ├─ view_manajemen_user
  ├─ manage_manajemen_user
  └─ export_manajemen_user

⚙️ Settings
  ├─ access_settings
  └─ manage_roles
```

---

## 🔒 Security Notes

### **Super Admin Protection**
- Super Admin **tidak bisa** dihapus rolenya
- Super Admin **selalu punya** semua permissions
- Tidak bisa remove permission dari super_admin

### **Role Protection**
- Role yang sedang digunakan tidak bisa dihapus
- Minimal 1 super admin harus ada di sistem

### **Permission Effect**
- Perubahan permission **langsung berlaku**
- User yang sedang login tidak perlu logout
- Refresh page untuk melihat perubahan

---

## 🎯 Best Practices

### **1. Test Dulu di Development**
Sebelum edit production, test dulu perubahan permission di development.

### **2. Dokumentasikan Perubahan**
Catat permission apa yang diubah dan alasannya.

### **3. Backup Before Major Changes**
Backup database sebelum mengubah banyak permissions.

### **4. Use Descriptive Role Names**
Jika buat role baru, gunakan nama yang jelas.

### **5. Group Related Permissions**
Permissions sudah dikelompokkan per fitur, jangan pisah-pisahkan.

---

## 📱 Real-Time Testing

### Test Permission Changes:

#### **Method 1: Via UI**
1. Edit permission role
2. Save
3. Buka tab baru dengan user role tersebut
4. Refresh page
5. Cek menu hilang/muncul

#### **Method 2: Via Tinker**
```bash
php artisan tinker
```

```php
// Check permission user tertentu
$user = User::find(5); // ID user
$user->can('export_permintaan'); // true/false

// List semua permissions user
$user->getAllPermissions()->pluck('name');

// List permissions role
Role::findByName('admin')->permissions->pluck('name');
```

---

## ⚠️ Troubleshooting

### **Permission tidak berubah?**
```bash
# Clear cache
php artisan optimize:clear
php artisan config:cache

# Refresh browser
```

### **Menu tidak muncul/hilang?**
Cek permission di RoleResource:
- `access_[nama_menu]` harus di-check
- Contoh: `access_stok_barang` untuk menu Stok Barang

### **Error saat save?**
- Pastikan minimal 1 permission ter-check
- Role name tidak boleh duplikat
- Super admin harus tetap punya semua permissions

---

## 📊 Default Permission Matrix

| Role | Permissions | Access Level |
|------|-------------|--------------|
| **super_admin** | 26 | Full Access + Manage Roles |
| **admin** | 21 | Manage All (except Roles) |
| **keuangan** | 20 | Finance Authority + Import |
| **user** | 10 | View & Create Only |

---

## ✨ Summary

✅ **Super Admin bisa edit permissions via website**  
✅ **Menu: Pengaturan → Roles & Permissions**  
✅ **URL: /app/shield/roles**  
✅ **UI friendly dengan grouping per fitur**  
✅ **Toggle on/off permissions dengan mudah**  
✅ **Perubahan langsung berlaku**  

**Sekarang Anda bisa mengelola access control tanpa coding!** 🎉
