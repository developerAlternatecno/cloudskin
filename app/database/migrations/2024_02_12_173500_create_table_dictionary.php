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
            $table->string('name');
            $table->enum('data_type', ['temperature','percentage','humidity', 'time','date']);
            $table->enum('input_type', ['int','double','string','float']);
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
