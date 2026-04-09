<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'nama_pembeli',
        'tiket_id',
        'jumlah',
        'total',
        'tanggal',
    ];

    // Relasi ke Tiket
    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }
}