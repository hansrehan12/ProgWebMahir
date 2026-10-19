<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengawas extends Model
{
    protected $table = 'pengawas';

    protected $fillable = [
        'nama',
        'alamat',
        'notelp',
        'email',
    ];
}
