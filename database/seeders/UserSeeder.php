<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $this->command->info('👥 Seeding Users...');

        $user1 = User::create([
            'name' => 'Admin Keuangan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'keuangan',
            'bagian_id' => 1,
        ]);
        $user1->assignRole('keuangan');

        $user2 = User::create([
            'name' => 'Admin Gudang Tata Usaha',
            'email' => 'gudangTU@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 1,
        ]);
        $user2->assignRole('admin');

        $user3 = User::create([
            'name' => 'Staf Tata Usaha',
            'email' => 'userTU@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 1,
        ]);
        $user3->assignRole('user');

        $user4 = User::create([
            'name' => 'Admin Gudang Bidang Survei dan Pemetaan',
            'email' => 'gudangSP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 2,
        ]);
        $user4->assignRole('admin');

        $user5 = User::create([
            'name' => 'Staf Bidang Survei dan Pemetaan',
            'email' => 'userSP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 2,
        ]);
        $user5->assignRole('user');

        $user6 = User::create([
            'name' => 'Admin Gudang Bidang Penetapan Hak dan Pendaftaran',
            'email' => 'gudangPHP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 3,
        ]);
        $user6->assignRole('admin');

        $user7 = User::create([
            'name' => 'Staf Bidang Penetapan Hak dan Pendaftaran',
            'email' => 'userPHP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 3,
        ]);
        $user7->assignRole('user');

        $user8 = User::create([
            'name' => 'Admin Gudang Bidang Penataan dan Pemberdayaan',
            'email' => 'gudangPP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 4,
        ]);
        $user8->assignRole('admin');

        $user9 = User::create([
            'name' => 'Staf Bidang Penataan dan Pemberdayaan',
            'email' => 'userPP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 4,
        ]);
        $user9->assignRole('user');

        $user10 = User::create([
            'name' => 'Admin Gudang Bidang Pengadaan Tanah dan Pengembangan',
            'email' => 'gudangPTP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 5,
        ]);
        $user10->assignRole('admin');

        $user11 = User::create([
            'name' => 'Staf Bidang Pengadaan Tanah dan Pengembangan',
            'email' => 'userPTP@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 5,
        ]);
        $user11->assignRole('user');

        $user12 = User::create([
            'name' => 'Admin Gudang Bidang Pengendalian dan Penanganan Sengketa',
            'email' => 'gudangPPS@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'bagian_id' => 6,
        ]);
        $user12->assignRole('admin');

        $user13 = User::create([
            'name' => 'Staf Bidang Pengendalian dan Penanganan Sengketa',
            'email' => 'userPPS@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'bagian_id' => 6,
        ]);
        $user13->assignRole('user');

        $user14 = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'super_admin',
            'bagian_id' => 1,
        ]);
        $user14->assignRole('super_admin');
        
        $this->command->info('✅ 14 users berhasil dibuat (1 super admin, 1 keuangan, 6 admin, 6 user)');
        $this->command->info('   Password semua user: 12345678');
    }
}
