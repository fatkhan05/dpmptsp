<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiHasPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai_has_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_user_id');
            $table->foreignId('pekerjaan_id');

            $table->string('latitude_sebelum')->nullable();
            $table->string('longitude_sebelum')->nullable();
            $table->string('foto_sebelum')->nullable();
            $table->dateTime('time_take_sebelum')->nullable();

            $table->string('latitude_sesudah')->nullable();
            $table->string('longitude_sesudah')->nullable();
            $table->string('foto_sesudah')->nullable();
            $table->dateTime('time_take_sesudah')->nullable();
            
            $table->integer('nilai')->nullable();
            $table->string('status')->default('belum');
            $table->string('komentar')->nullable();
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
        Schema::dropIfExists('pegawai_has_pekerjaans');
    }
}
