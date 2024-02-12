<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterTableDatasetAuxiliarData extends Migration
{
    public function up()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->string('owner')->nullable()->after('name');
            $table->string('origin')->nullable()->after('owner');
            $table->string('categorie')->nullable()->after('license');
            $table->date('start_daterange')->default('1900-01-01')->after('origin');
            $table->date('end_daterange')->default('1900-01-01')->after('start_daterange');
            $table->string('imagen')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->dropColumn('owner');
            $table->dropColumn('origin');
            $table->dropColumn('categorie');
            $table->dropColumn('start_daterange');
            $table->dropColumn('end_daterange');
            $table->dropColumn('imagen');
        });
    }
}
