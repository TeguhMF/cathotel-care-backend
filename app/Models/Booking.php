<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'room_id',
        'customer_name',
        'customer_phone',
        'cat_name',
        'cat_breed',
        'check_in',
        'check_out',
        'total_nights',
        'total_price',
        'dp_amount',
        'status',
        'notes',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}