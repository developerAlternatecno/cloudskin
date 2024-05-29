<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterTableProject extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('dataset_reference_id')->nullable()->after('access');
        });
    
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('dataset_reference_id');
        });
    }
}
