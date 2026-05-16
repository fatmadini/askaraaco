<?php
// database/migrations/2026_01_01_000000_add_barcode_to_transaksis.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('barcode')->nullable()->unique()->after('kode_unik');
            $table->string('barcode_path')->nullable()->after('barcode');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'barcode_path']);
        });
    }
};