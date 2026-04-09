<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $fillable = [
        'konser_id',
        'kategori',
        'harga',
        'stok',
    ];

    // Relasi ke Konser
    public function konser()
    {
        return $this->belongsTo(Konser::class);
    }

    // Relasi ke Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}