<?php

namespace Database\Seeders;

use App\Models\LogAktivitas;
use App\Models\Gudang;
use App\Models\User;
use Illuminate\Database\Seeder;

class LogAktivitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat sample log aktivitas untuk demo:
     * - Barang Masuk
     * - Barang Keluar
     * - Penyesuaian Stok
     */
    public function run(): void
    {
        $this->command->info('📝 Seeding Log Aktivitas...');

        $adminKeuangan = User::where('role', 'keuangan')->first();
        $adminTU = User::where('role', 'admin')->where('bagian_id', 1)->first();
        $userTU = User::where('role', 'user')->where('bagian_id', 1)->first();

        $logs = [];

        // 1. Log Barang Masuk - Admin Keuangan menambah stok Pensil di TU
        $gudang1 = Gudang::where('bagian_id', 1)->where('barang_id', 1)->first();
        if ($gudang1 && $adminKeuangan) {
            $stokLama = $gudang1->stok - 10;
            LogAktivitas::create([
                'barang_id' => 1,
                'user_id' => $adminKeuangan->id,
                'gudang_id' => $gudang1->id,
                'nama_barang_snapshot' => 'Pensil',
                'kode_barang_snapshot' => 'B001',
                'user_snapshot' => $adminKeuangan->name,
                'nama_bagian_snapshot' => 'Tata Usaha',
                'tipe' => 'Masuk',
                'jumlah' => 10,
                'stok_awal' => $stokLama,
                'stok_akhir' => $gudang1->stok,
                'keterangan' => 'Pembelian stok baru',
                'created_at' => now()->subDays(15),
            ]);
            $logs[] = 'Masuk: +10 Pensil (TU)';
        }

        // 2. Log Barang Keluar - User TU mengambil Pensil
        if ($gudang1 && $userTU) {
            $stokLama = $gudang1->stok + 5;
            LogAktivitas::create([
                'barang_id' => 1,
                'user_id' => $userTU->id,
                'gudang_id' => $gudang1->id,
                'nama_barang_snapshot' => 'Pensil',
                'kode_barang_snapshot' => 'B001',
                'user_snapshot' => $userTU->name,
                'nama_bagian_snapshot' => 'Tata Usaha',
                'tipe' => 'Keluar',
                'jumlah' => 5,
                'stok_awal' => $stokLama,
                'stok_akhir' => $gudang1->stok,
                'keterangan' => 'Permintaan barang disetujui',
                'created_at' => now()->subDays(10),
            ]);
            $logs[] = 'Keluar: -5 Pensil (TU)';
        }

        // 3. Log Barang Masuk - Admin TU menambah stok Buku
        $gudang2 = Gudang::where('bagian_id', 1)->where('barang_id', 2)->first();
        if ($gudang2 && $adminTU) {
            $stokLama = $gudang2->stok - 20;
            LogAktivitas::create([
                'barang_id' => 2,
                'user_id' => $adminTU->id,
                'gudang_id' => $gudang2->id,
                'nama_barang_snapshot' => 'Buku',
                'kode_barang_snapshot' => 'B002',
                'user_snapshot' => $adminTU->name,
                'nama_bagian_snapshot' => 'Tata Usaha',
                'tipe' => 'Masuk',
                'jumlah' => 20,
                'stok_awal' => $stokLama,
                'stok_akhir' => $gudang2->stok,
                'keterangan' => 'Pengadaan rutin bulanan',
                'created_at' => now()->subDays(12),
            ]);
            $logs[] = 'Masuk: +20 Buku (TU)';
        }

        // 4. Log Penyesuaian - Admin Keuangan menyesuaikan stok Kertas A4
        $gudang3 = Gudang::where('bagian_id', 2)->where('barang_id', 3)->first();
        if ($gudang3 && $adminKeuangan) {
            $stokLama = $gudang3->stok - 5;
            LogAktivitas::create([
                'barang_id' => 3,
                'user_id' => $adminKeuangan->id,
                'gudang_id' => $gudang3->id,
                'nama_barang_snapshot' => 'Kertas A4',
                'kode_barang_snapshot' => 'B003',
                'user_snapshot' => $adminKeuangan->name,
                'nama_bagian_snapshot' => 'Survei dan Pemetaan',
                'tipe' => 'Penyesuaian',
                'jumlah' => 5,
                'stok_awal' => $stokLama,
                'stok_akhir' => $gudang3->stok,
                'keterangan' => 'Koreksi stock opname',
                'created_at' => now()->subDays(8),
            ]);
            $logs[] = 'Penyesuaian: +5 Kertas A4 (SP)';
        }

        // 5. Log Barang Keluar - Admin mengurangi stok Galon
        $gudang4 = Gudang::where('bagian_id', 3)->where('barang_id', 4)->first();
        $adminPHP = User::where('role', 'admin')->where('bagian_id', 3)->first();
        if ($gudang4 && $adminPHP) {
            $stokLama = $gudang4->stok + 3;
            LogAktivitas::create([
                'barang_id' => 4,
                'user_id' => $adminPHP->id,
                'gudang_id' => $gudang4->id,
                'nama_barang_snapshot' => 'Galon',
                'kode_barang_snapshot' => 'B004',
                'user_snapshot' => $adminPHP->name,
                'nama_bagian_snapshot' => 'Penetapan Hak dan Pendaftaran',
                'tipe' => 'Keluar',
                'jumlah' => 3,
                'stok_awal' => $stokLama,
                'stok_akhir' => $gudang4->stok,
                'keterangan' => 'Pemakaian rutin',
                'created_at' => now()->subDays(5),
            ]);
            $logs[] = 'Keluar: -3 Galon (PHP)';
        }

        // 6. Log Barang Masuk - Keuangan menambah Dispenser
        $gudang5 = Gudang::where('bagian_id', 4)->where('barang_id', 5)->first();
        if ($gudang5 && $adminKeuangan) {
            $stokLama = $gudang5->stok - 15;
            LogAktivitas::create([
                'barang_id' => 5,
                'user_id' => $adminKeuangan->id,
                'gudang_id' => $gudang5->id,
                'nama_barang_snapshot' => 'Dispenser',
                'kode_barang_snapshot' => 'B005',
                'user_snapshot' => $adminKeuangan->name,
                'nama_bagian_snapshot' => 'Penataan dan Pemberdayaan',
                'tipe' => 'Masuk',
                'jumlah' => 15,
                'stok_awal' => $stokLama,
                'stok_akhir' => $gudang5->stok,
                'keterangan' => 'Pembelian aset baru',
                'created_at' => now()->subDays(3),
            ]);
            $logs[] = 'Masuk: +15 Dispenser (PP)';
        }

        // 7. Log Penyesuaian - Admin mengurangi stok rusak
        $gudang6 = Gudang::where('bagian_id', 5)->where('barang_id', 6)->first();
        $adminPTP = User::where('role', 'admin')->where('bagian_id', 5)->first();
        if ($gudang6 && $adminPTP) {
            $stokLama = $gudang6->stok + 2;
            LogAktivitas::create([
                'barang_id' => 6,
                'user_id' => $adminPTP->id,
                'gudang_id' => $gudang6->id,
                'nama_barang_snapshot' => 'Binder',
                'kode_barang_snapshot' => 'B006',
                'user_snapshot' => $adminPTP->name,
                'nama_bagian_snapshot' => 'Pengadaan Tanah dan Pengembangan',
                'tipe' => 'Penyesuaian',
                'jumlah' => -2,
                'stok_awal' => $stokLama,
                'stok_akhir' => $gudang6->stok,
                'keterangan' => 'Barang rusak/hilang',
                'created_at' => now()->subDays(2),
            ]);
            $logs[] = 'Penyesuaian: -2 Binder rusak (PTP)';
        }

        $count = LogAktivitas::count();
        $this->command->info("✅ {$count} log aktivitas berhasil dibuat");
        foreach ($logs as $log) {
            $this->command->line('   ' . $log);
        }
    }
}
