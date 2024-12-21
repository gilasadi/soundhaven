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
        Schema::table('likes', function (Blueprint $table) {
            $table->boolean('liked')->default(true); // Tambahkan kolom liked
        });
    }

    public function down()
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropColumn('liked');
        });
    }
};
