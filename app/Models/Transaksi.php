<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaksi extends Model
{
    protected $fillable = [
        'nama_pembeli',
        'tiket_id',
        'jumlah',
        'total',
        'tanggal',
        'metode_pembayaran',
        'status_pembayaran',
        'kode_unik',
        'user_id',
        'barcode',
        'barcode_path'
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',     
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($transaksi) {
            $transaksi->generateQRCode();
        });
    }

    public function generateQRCode()
    {
        $this->barcode = 'TIX' . $this->id . time() . Str::random(6);
        
        $path = storage_path('app/public/qrcodes/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $konser = $this->tiket->konser ?? null;
        
        $qrData = json_encode([
            'id' => $this->id,
            'barcode' => $this->barcode,
            'pembeli' => $this->nama_pembeli,
            'konser' => $konser->nama_konser ?? 'Konser',
            'tanggal_konser' => $konser->tanggal ?? $this->tanggal->format('Y-m-d'),
            'jam_konser' => $konser->waktu_mulai ?? '19:00',
            'tanggal_beli' => $this->tanggal ? $this->tanggal->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
            'lokasi' => $konser->lokasi ?? '-',
            'kategori' => $this->tiket->kategori ?? '-',
            'jumlah' => $this->jumlah,
            'kode_unik' => $this->kode_unik,
            'total' => $this->total
        ]);
        
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrData);

        $qrImage = @file_get_contents($qrApiUrl);
        
        if ($qrImage === false) {
            $this->barcode_path = null;
            $this->saveQuietly();
            return $this;
        }
        $filename = 'qrcode_' . $this->id . '_' . time() . '.png';
        file_put_contents($path . $filename, $qrImage);
        
        $this->barcode_path = 'storage/qrcodes/' . $filename;
        $this->saveQuietly();
        
        return $this;
    }
    public function regenerateQRCode()
    {
        if ($this->barcode_path && file_exists(public_path($this->barcode_path))) {
            @unlink(public_path($this->barcode_path));
        }

        $this->barcode_path = null;
        $this->saveQuietly();

        return $this->generateQRCode();
    }

    public function hasQRCode()
    {
        return $this->barcode_path && file_exists(public_path($this->barcode_path));
    }

    public function getQRCodeUrl()
    {
        if ($this->hasQRCode()) {
            return asset($this->barcode_path);
        }
        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($this->kode_unik);
    }

    public function getJamKonserAttribute()
    {
        $konser = $this->tiket->konser ?? null;
        if ($konser && $konser->waktu_mulai) {
            return \Carbon\Carbon::parse($konser->waktu_mulai)->format('H:i');
        }
        return '19:00';
    }

    public function getTanggalKonserFormattedAttribute()
    {
        $konser = $this->tiket->konser ?? null;
        if ($konser && $konser->tanggal) {
            return \Carbon\Carbon::parse($konser->tanggal)->translatedFormat('d F Y');
        }
        return '-';
    }

    public function getTanggalBeliFormattedAttribute()
    {
        if ($this->tanggal) {
            return $this->tanggal->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i');
        }
        if ($this->created_at) {
            return $this->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i');
        }
        return now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i');
    }

    public function getWaktuBeliAttribute()
    {
        if ($this->tanggal) {
            return $this->tanggal->setTimezone('Asia/Jakarta')->format('H:i:s');
        }
        if ($this->created_at) {
            return $this->created_at->setTimezone('Asia/Jakarta')->format('H:i:s');
        }
        return now()->format('H:i:s');
    }
}