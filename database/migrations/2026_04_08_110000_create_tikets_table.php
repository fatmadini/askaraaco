<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('tikets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('konser_id')->constrained('konsers')->cascadeOnDelete();
    $table->string('kategori');
    $table->integer('harga');
    $table->integer('stok');
    $table->timestamps();
});
}
};
