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
        'kode_pegawai',
        'department',
        'unit',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_peminjaman',
    ];

    public function laptop()
    {
        return $this->belongsTo(LaptopData::class, 'laptop_id');
    }
}
