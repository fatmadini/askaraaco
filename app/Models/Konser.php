<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konser extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_konser',
        'tanggal',
        'lokasi',
        'kota',
        'harga',
        'kuota',
        'foto',
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::saving(function ($konser) {
            if (!empty($konser->lokasi)) {
                $konser->kota = self::detectKota($konser->lokasi);
            }
        });
    }

    public static function detectKota($lokasi)
    {
        $lokasiLower = strtolower(trim($lokasi));

        $kotaKeywords = [
            'Jakarta'    => [
                'gbk', 'gelora bung karno', 'ice bsd', 'jiexpo', 'kemayoran',
                'jakarta', 'jkta', 'gambir', 'senayan', 'tanah abang',
                'mangga dua', 'glodok', 'blok m', 'sudirman', 'thamrin',
                'kuningan', 'cikini', 'menteng', 'ancol', 'kelapa gading',
            ],
            'Palembang'  => [
                'jakabaring', 'kuto besak', 'talang betutu',
                'palembang', 'plg', 'seberang ulu', 'ilir barat',
                'ilir timur', 'sukarami', 'kalidoni', 'bukit besar',
            ],
            'Bali'       => [
                'ngurah rai', 'kuta', 'seminyak', 'nusa dua', 'canggu',
                'ubud', 'sanur', 'jimbaran', 'denpasar', 'gianyar',
                'badung', 'tabanan', 'bali',
            ],
            'Yogyakarta' => [
                'malioboro', 'parangtritis', 'prambanan', 'kaliurang',
                'yogyakarta', 'jogja', 'yogya', 'jogjakarta',
                'bantul', 'sleman', 'gunungkidul', 'kulonprogo',
            ],
            'Bandung'    => [
                'dago', 'braga', 'cihampelas', 'trans studio bandung',
                'bandung', 'bdg', 'dayeuhkolot', 'cimahi', 'lembang',
                'ciwidey', 'setiabudi', 'buah batu', 'kopo',
                'soreang', 'rancaekek',
            ],
            'Surabaya'   => [
                'delta plaza', 'grand city surabaya', 'gor bung tomo',
                'surabaya', 'sby', 'gubeng', 'tunjungan', 'wonokromo',
                'kenjeran', 'rungkut', 'mulyorejo', 'tandes',
                'pabean', 'krembangan',
            ],
            'Semarang'   => [
                'simpang lima', 'pandansari', 'tembalang',
                'semarang', 'smg', 'candisari', 'gajahmungkur',
                'pedurungan', 'genuk', 'gunungpati', 'banyumanik',
            ],
            'Medan'      => [
                'polonia', 'maimun', 'medan', 'belawan', 'binjai',
                'lubuk pakam', 'pematang siantar', 'tebing tinggi',
            ],
            'Makassar'   => [
                'karebosi', 'pantai losari',
                'makassar', 'mks', 'ujung pandang', 'maros',
                'gowa', 'takalar',
            ],
            'Malang'     => [
                'arema', 'malang', 'mlg', 'batu', 'kepanjen',
                'singosari', 'lawang', 'turen',
            ],
            'Lombok'     => [
                'gili', 'senggigi', 'praya',
                'lombok', 'mataram', 'selong',
            ],
            'Ambon'      => [
                'pattimura', 'ambon', 'namalatu', 'wayame',
                'batu merah', 'passo', 'halong',
            ],
            'Papua'      => [
                'raja ampat', 'waisai',
                'papua', 'jayapura', 'biak', 'merauke',
                'timika', 'nabire', 'manokwari', 'sorong',
            ],
            'Aceh'       => [
                'banda aceh', 'meulaboh', 'lhokseumawe',
                'aceh', 'langsa', 'sigli', 'takengon',
            ],
            'Padang'     => [
                'bukittinggi', 'payakumbuh',
                'padang', 'pariaman', 'solok', 'sawahlunto',
            ],
            'Pekanbaru'  => [
                'pekanbaru', 'pkb', 'dumai', 'bengkalis',
            ],
            'Banjarmasin' => [
                'banjarmasin', 'bjm', 'banjarbaru', 'martapura',
            ],
            'Pontianak'  => [
                'pontianak', 'ptk', 'singkawang', 'sambas',
            ],
            'Manado'     => [
                'bunaken', 'manado', 'mdo', 'bitung', 'tomohon',
            ],
            'Kupang'     => [
                'kupang', 'kpg', 'ende', 'maumere',
            ],
        ];

        foreach ($kotaKeywords as $kota => $keywords) {
            foreach ($keywords as $keyword) {
                // Gunakan strpos untuk pencocokan substring
                if (strpos($lokasiLower, strtolower($keyword)) !== false) {
                    return $kota;
                }
            }
        }

        return null;
    }
}