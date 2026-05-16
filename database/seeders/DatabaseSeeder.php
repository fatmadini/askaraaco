<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@ticketwave.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_telepon' => '081234567890',
        ]);

        // Customer Contoh
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'no_telepon' => '081298765432',
        ]);
    }
}