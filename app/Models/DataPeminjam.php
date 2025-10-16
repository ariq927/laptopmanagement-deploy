<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPeminjam extends Model
{
    use HasFactory;

    protected $table = 'data_peminjam';

    protected $fillable = [
        'user_id',
        'laptop_id',
        'nama',
        'department',
        'nomor_telepon',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function laptop()
    {
        return $this->belongsTo(LaptopData::class, 'laptop_id');
    }
}
