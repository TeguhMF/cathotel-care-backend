<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::create([
            'name' => 'Standard Room',
            'description' => 'Fasilitas kandang nyaman, makan 2x sehari, area bermain reguler, dan ruangan ber-AC.',
            'price_per_night' => 50000,
            'capacity' => 1,
            'status' => 'available',
        ]);

        Room::create([
            'name' => 'Deluxe Room',
            'description' => 'Ruangan lebih luas, makan 3x sehari + snack, akses CCTV 24/7, dan perawatan sisir bulu harian.',
            'price_per_night' => 85000,
            'capacity' => 2,
            'status' => 'available',
        ]);

        Room::create([
            'name' => 'VIP Suite',
            'description' => 'Kamar privat ekstra luas, makanan premium (free request), grooming gratis, arena main privat, & laporan video harian.',
            'price_per_night' => 150000,
            'capacity' => 3,
            'status' => 'available',
        ]);
    }
}