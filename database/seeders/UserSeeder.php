<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Akun Admin (Role tertinggi)
        User::create([
            'username' => 'admin_renthub',
            'email' => 'admin@renthub.com',
            'password' => 'qwer1234',
            'role' => 'admin', 
        ]);

        // 2. Akun Vendor (Pemilik barang)
        User::create([
            'username' => 'vendor_aset',
            'email' => 'vendor@renthub.com',
            'password' => 'qwer1234',
            'role' => 'vendor', 
        ]);
        
        // 3. Akun Customer (Penyewa default)
        User::create([
            'username' => 'customer_baru',
            'email' => 'customer@renthub.com',
            'password' => 'qwer1234',
            'role' => 'customer', 
        ]);
    }
}