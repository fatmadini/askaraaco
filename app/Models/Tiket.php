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

    public function konser()
    {
        return $this->belongsTo(Konser::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}