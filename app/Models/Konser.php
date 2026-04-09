<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konser extends Model
{
    protected $fillable = [
        'nama_konser',
        'tanggal',
        'lokasi',
        'harga',
        'kuota',
        'foto',
    ];

    // Relasi ke Tiket
    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
}