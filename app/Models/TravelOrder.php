<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'destination', 'departure_date', 'return_date', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
