<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Mengambil semua daftar kamar yang tersedia
    public function index()
    {
        $rooms = Room::where('status', 'available')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar kamar berhasil diambil',
            'data' => $rooms
        ], 200);
    }

    // Detail kamar berdasarkan ID
    public function show($id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json([
                'success' => false, 
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true, 
            'data' => $room
        ], 200);
    }
}