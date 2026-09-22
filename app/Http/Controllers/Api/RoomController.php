<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::where('status', 'available')->get();
        return response()->json([
            'success' => true,
            'data' => $rooms
        ]);
    }
}