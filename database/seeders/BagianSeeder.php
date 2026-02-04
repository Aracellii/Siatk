<?php

namespace Database\Seeders;

use App\Models\Bagian;
use Illuminate\Database\Seeder;

class BagianSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📁 Seeding Bagian/Unit Kerja...');
        
        $data = [
            ['id' => 1, 'nama_bagian' => 'Tata Usaha'],
            ['id' => 2, 'nama_bagian' => 'Survei dan Pemetaan'],
            ['id' => 3, 'nama_bagian' => 'Penetapan Hak dan Pendaftaran'],
            ['id' => 4, 'nama_bagian' => 'Penataan dan Pemberdayaan'],
            ['id' => 5, 'nama_bagian' => 'Pengadaan Tanah dan Pengembangan'],
            ['id' => 6, 'nama_bagian' => 'Pengendalian dan Penanganan Sengketa'],
        ];

        foreach ($data as $item) {
            Bagian::create(['id' => $item['id'], 'nama_bagian' => $item['nama_bagian']]);
        }
        
        $this->command->info('✅ ' . count($data) . ' bagian berhasil dibuat');
    }
}