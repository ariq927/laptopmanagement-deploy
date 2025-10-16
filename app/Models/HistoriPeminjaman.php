<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriPeminjaman extends Model
{
    protected $table = 'histori_peminjaman';

    protected $fillable = [
        'user_id',
        'laptop_id',
        'nama',
        'department',
        'tanggal_mulai',
        'tanggal_selesai',
        'nomor_telepon',
        'status',
    ];

    public function laptop()
    {
        return $this->belongsTo(LaptopData::class, 'laptop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
