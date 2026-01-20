<?php

namespace Database\Seeders;

use App\Models\Bagian;
use Illuminate\Database\Seeder;

class BagianSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'nama_bagian' => 'Bagian Tata Usaha'],
            ['id' => 2, 'nama_bagian' => 'Bidang Survei dan Pemetaan'],
            ['id' => 3, 'nama_bagian' => 'Bidang Penetapan Hak dan Pendaftaran'],
            ['id' => 4, 'nama_bagian' => 'Bidang Penataan dan Pemberdayaan'],
            ['id' => 5, 'nama_bagian' => 'Bidang Pengadaan Tanah dan Pengembangan'],
            ['id' => 6, 'nama_bagian' => 'Bidang Pengendalian dan Penanganan Sengketa'],
        ];

        foreach ($data as $item) {
            Bagian::create(['id' => $item['id'], 'nama_bagian' => $item['nama_bagian']]);
        }
    }
}