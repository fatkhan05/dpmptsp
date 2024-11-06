<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerizinansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perizinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_izin_id');
            $table->foreignId('detail_user_id');
            $table->dateTime('mulai');
            $table->dateTime('selesai')->nullable()->comment('null jika lama izin tidak diketahui');
            $table->text('keterangan')->nullable();
            $table->string('status')->nullable()->comment('null= belum ada kepastian, 1= terima, 0= tolak');
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
        Schema::dropIfExists('perizinans');
    }
}
