<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Dataset;

class AlterTableDatasets extends Migration
{
    public function up()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->boolean('autovalidate_sales')->nullable()->after('is_geolocated');
        });

        Dataset::whereNull('autovalidate_sales')->update(['autovalidate_sales' => false]);
    }

    public function down()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->dropColumn('autovalidate_sales');
        });
    }
}
