<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->longText('bidang_deskripsi');
            $table->timestamps();
        });
        Schema::table('kategori_pegawais', function (Blueprint $table) {
            $table->foreignId('bidang_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bidang');
        Schema::table('kategori_pegawais', function (Blueprint $table) {
            $table->removeColumn('bidang_id')->nullable();
        });
    }
}