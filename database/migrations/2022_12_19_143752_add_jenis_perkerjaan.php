<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisPerkerjaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pegawai_has_pekerjaans', function (Blueprint $table) {
            $table->string('jenis_pekerjaan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pegawai_has_pekerjaans', function (Blueprint $table) {
            $table->dropColumn('jenis_pekerjaan');
        });
    }
}