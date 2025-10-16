<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDetail extends Model
{
    use HasFactory;

    protected $table = 'users_detail';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'phone',
        'department',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
