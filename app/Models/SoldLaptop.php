<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldLaptop extends Model
{
    use HasFactory;

    protected $fillable = [
        'laptop_id',
        'buyer_name',
        'buyer_id',
        'sold_price',
        'notes',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime',
        'sold_price' => 'decimal:2',
    ];

    public function laptop()
    {
        return $this->belongsTo(LaptopData::class, 'laptop_id');
    }
}