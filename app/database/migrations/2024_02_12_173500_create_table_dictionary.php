<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDictionary extends Migration
{
    public function up()
    {
        Schema::create('dictionary', function (Blueprint $table) {
            $table->id();
            $table->string('data_type')->unique();;
            $table->string('default_unit')->nullable(false)->default(null);
            $table->text('description')->nullable()->default(null);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dictionary');
    }
}
