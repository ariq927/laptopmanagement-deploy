<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaptopData extends Model
{
    use HasFactory;

    protected $table = 'laptop_data';

    protected $fillable = [
        'merek',
        'tipe',
        'spesifikasi',
        'serial_number',
        'stok',
        'status', 
        'foto',
        'public_id'
    ];
}
