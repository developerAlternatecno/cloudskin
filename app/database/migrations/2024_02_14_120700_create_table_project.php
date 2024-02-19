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

            $table->timestamps();
        });

        // Tabla intermedia para la relación muchos a muchos
        Schema::create('project_dataset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->string('dataset_id', 255)->constrained('datasets'); // Cambiado a foreignId
            $table->timestamps();
        });

        Schema::table('datasets', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->constrained()->after('data_url');
        });
    }


    public function down()
    {
        Schema::dropIfExists('project_dataset');
        Schema::dropIfExists('projects');
    }
}
