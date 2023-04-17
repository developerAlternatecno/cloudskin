<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')->constrained('users');

            $table->string("engine_id")->nullable();
            $table->foreign("engine_id")->references('id')->on('engines');

            $table->string('name')->nullable();
            $table->enum('type', ['buyout', 'rental'])->nullable();
            $table->float('price')->nullable();
            $table->enum('license', ['national', 'european', 'unlimited'])->nullable();
            $table->text('description')->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasets');
    }
}
