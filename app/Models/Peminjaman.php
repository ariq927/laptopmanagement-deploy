<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'data_peminjam';  
    protected $fillable = [
        'user_id', 'laptop_id', 'tanggal_mulai', 'tanggal_selesai'
    ];

    public function laptop()
    {
        return $this->belongsTo(LaptopData::class, 'laptop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'username'); 
    }
}
