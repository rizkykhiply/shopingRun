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
        'kondisi_id',
        'poin',
        'user_id',
    ];

    public function kondisi1()
    {
        return $this->belongsTo(Kondisi::class, 'kondisi_id');
    }

}
