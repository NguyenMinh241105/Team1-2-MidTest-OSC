<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('san_pham', function (Blueprint $table) {
            $table->string('code', 50)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('san_pham', function (Blueprint $table) {
            $table->string('code', 50)->nullable(false)->change();
        });
    }
};