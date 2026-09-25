<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Account Admin
        User::updateOrCreate(
            ['username' => 'admin010'],
            [
                'name' => 'Administrator',
                'email' => 'admin@cathotel.com',
                'phone' => '081234567890',
                'password' => Hash::make('05TPLP010'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Data Kamar
        $this->call([
            RoomSeeder::class,
        ]);
    }
}