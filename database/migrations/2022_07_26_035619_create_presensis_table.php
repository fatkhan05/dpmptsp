<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_user_id');
            
            $table->dateTime('masuk');
            $table->string('telat');
            $table->string('lokasi_presensi_masuk');
            $table->string('latitude_masuk');
            $table->string('longitude_masuk');
            $table->string('foto_masuk');
            
            $table->dateTime('pulang')->nullable();
            $table->string('pulang_cepat')->nullable();
            $table->string('lokasi_presensi_pulang')->nullable();
            $table->string('latitude_pulang')->nullable();
            $table->string('longitude_pulang')->nullable();
            $table->string('foto_pulang')->nullable();
            
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
        Schema::dropIfExists('presensis');
    }
}
