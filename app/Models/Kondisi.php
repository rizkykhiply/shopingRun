<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kondisi extends Model
{
    protected $table = 'conditions';
    protected $fillable = [
        'namaKond',
        'jmlPembagi',
        'jmlMin',
        'minPoin',
        'maxPoin',
    ];


}
