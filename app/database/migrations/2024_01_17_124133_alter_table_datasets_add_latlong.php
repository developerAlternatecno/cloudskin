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
            $table->enum('data_type', ['Static Data', 'Real Time Data'])->nullable()->after('provider_doc');
            $table->longText('data_url')->nullable()->after('data_type');
        });
    }

    public function down()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('data_type');
            $table->dropColumn('data_url');
        });
    }
}
