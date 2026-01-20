<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public function run(): void
    {
 
        User::create([
            'name' => 'Admin Keuangan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'keuangan',
            'bagian_id' => 1,
        ]);

        User::create([
            'name' => 'Admin Gudang Tata Usaha',
            'email' => 'gudangTU@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 1,
        ]);

        User::create([
            'name' => 'Staf Tata Usaha',
            'email' => 'userTU@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 1,
        ]);

        User::create([
            'name' => 'Admin Gudang Bidang Survei dan Pemetaan',
            'email' => 'gudangSP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 2,
        ]);

        User::create([
            'name' => 'Staf Bidang Survei dan Pemetaan',
            'email' => 'userSP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 2,
        ]);

        User::create([
            'name' => 'Admin Gudang Bidang Penetapan Hak dan Pendaftaran',
            'email' => 'gudangPHP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 3,
        ]);

        User::create([
            'name' => 'Staf Bidang Penetapan Hak dan Pendaftaran',
            'email' => 'userPHP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 3,
        ]);

        User::create([
            'name' => 'Admin Gudang Bidang Penataan dan Pemberdayaan',
            'email' => 'gudangPP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 4,
        ]);

        User::create([
            'name' => 'Staf Bidang Penataan dan Pemberdayaan',
            'email' => 'userPP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 4,
        ]);

        User::create([
            'name' => 'Admin Gudang Bidang Pengadaan Tanah dan Pengembangan',
            'email' => 'gudangPTP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 5,
        ]);

        User::create([
            'name' => 'Staf Bidang Pengadaan Tanah dan Pengembangan',
            'email' => 'userPTP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 5,
        ]);

        User::create([
            'name' => 'Admin Gudang Bidang Pengendalian dan Penanganan Sengketa',
            'email' => 'gudangPPS@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 6,
        ]);

        User::create([
            'name' => 'Staf Bidang Pengendalian dan Penanganan Sengketa',
            'email' => 'userPPS@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 6,
        ]);
    }
}