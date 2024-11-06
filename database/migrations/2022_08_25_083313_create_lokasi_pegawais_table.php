<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLokasiPegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lokasi_pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_user_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('lokasi')->comment('Luar / Dalam');
            $table->dateTime('waktu');
            $table->integer('status')->comment('1 = diizinkan or 0 = tidak diizinkan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lokasi_pegawais');
    }
}
