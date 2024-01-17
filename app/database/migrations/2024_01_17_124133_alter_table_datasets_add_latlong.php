<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDatasetsAddLatLong extends Migration
{
    public function up()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->string('latitude')->nullable()->after('is_geolocated');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
