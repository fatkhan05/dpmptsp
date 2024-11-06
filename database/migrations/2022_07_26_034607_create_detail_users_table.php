<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('nama');
            $table->string('photo')->nullable();
            $table->string('nik');
            $table->string('jenis_kelamin')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->date('tgl_mulai_kerja')->nullable();
            // $table->foreignId('kategori_pegawai_id');
            $table->text('alamat')->nullable();
            $table->foreignId('shift_id')->default(1);
            $table->foreignId('provinsi_id')->nullable();
            $table->foreignId('kabupaten_id')->nullable();
            $table->foreignId('kecamatan_id')->nullable();
            $table->foreignId('desa_id')->nullable();
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
        Schema::dropIfExists('detail_users');
    }
}
