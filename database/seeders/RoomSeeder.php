<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Standard Cozy Room',
                'category' => 'Standard',
                'price_per_night' => 85000,
                'capacity' => 1,
                'description' => 'Kamar nyaman ber-AC dengan mainan dasar, tempat tidur empuk, dan pembersihan rutin 2x sehari.',
                'image' => 'public/kandang 1.png',
                'status' => 'available',
            ],
            [
                'name' => 'Deluxe Playful Suite',
                'category' => 'Deluxe',
                'price_per_night' => 150000,
                'capacity' => 2,
                'description' => 'Kamar luas dengan cat tower, scratching post, kamera CCTV online 24 jam, dan makanan premium.',
                'image' => 'public/kandang 2.png',
                'status' => 'available',
            ],
            [
                'name' => 'VIP Presidential Suite',
                'category' => 'VIP',
                'price_per_night' => 250000,
                'capacity' => 4,
                'description' => 'Ruangan mewah paling luas, balkon kaca khusus, fasilitas spa & grooming gratis, serta akses CCTV privat untuk pemilik.',
                'image' => 'public/kandang 3.png',
                'status' => 'available',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}