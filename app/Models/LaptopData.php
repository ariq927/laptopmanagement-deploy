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
        'kode',
        'stok',
        'status', 
        'foto',
        'public_id',
        'keterangan'
    ];

    public function peminjamanAktif()
    {
        return $this->hasOne(DataPeminjam::class, 'laptop_id')
                    ->where('status_peminjaman', 'active');
    }
}
