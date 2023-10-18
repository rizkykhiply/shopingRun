<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    protected $fillable = [
        'nama',
        'nik',
        'alamat',
        'hp',
        'rek',
        'pekerjaan',
        'saldo',
        'poin',
        'user_id',
    ];


}
