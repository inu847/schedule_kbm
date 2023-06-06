<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generate extends Model
{
    use HasFactory;

    protected $table = 'generate';

    protected $fillable = [
        'kode_mapel',
        'mapel',
        'kelas',
        'ruang',
        'nama_guru',
        'hari',
        'waktu',
    ];
}
