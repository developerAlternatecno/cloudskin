<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProject  extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Public Research Project', 'Private Research Project', 'Commercial Project']);
            $table->text('description')->nullable()->default(null);
            $table->string('entity')->nullable()->default(null);
            $table->string('url')->nullable()->default(null);
            $table->string('access')->nullable()->default(null);
            $table->string('data_source')->nullable()->default(null);
            $table->json('dataset')->nullable()->default(null);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
