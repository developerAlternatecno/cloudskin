<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterTableDatasets extends Migration
{
    public function up()
    {
        // Actualizar el tipo de columna 'type' a ENUM
        DB::statement("ALTER TABLE datasets MODIFY COLUMN type ENUM('sale', 'rental', 'free') DEFAULT NULL");

        // Actualizar el tipo de columna 'license' a ENUM con nuevas opciones
        DB::statement("ALTER TABLE datasets MODIFY COLUMN license ENUM('Unrestricted public use license', 'Public use license - include use restrictions', 'Public use license with geographic restrictions', 'Proprietary license - include usage restrictions', 'Proprietary license - include geographic restrictions') DEFAULT NULL");

        // Añadir la nueva columna booleana
        Schema::table('datasets', function (Blueprint $table) {
            $table->boolean('autovalidate_sales')->nullable()->after('is_geolocated');
        });

        // Actualizar registros existentes para cambiar 'buyout' a 'sale' en la columna "type"
        DB::table('datasets')->where('type', 'buyout')->update(['type' => 'sale']);

        // Actualizar registros existentes con un valor predeterminado para la nueva columna "autovalidate_sales"
        DB::table('datasets')->whereNull('autovalidate_sales')->update(['autovalidate_sales' => false]);
    }

    public function down()
    {
        // Revertir los registros existentes a 'buyout' en la columna "type"
        DB::table('datasets')->where('type', 'sale')->update(['type' => 'buyout']);

        // Revertir los registros existentes a 'national' en la columna "license"
        DB::statement("UPDATE datasets SET license = 'national' WHERE license = 'Unrestricted public use license'");
        DB::statement("UPDATE datasets SET license = 'european' WHERE license = 'Public use license - include use restrictions'");
        DB::statement("UPDATE datasets SET license = 'unlimited' WHERE license = 'Public use license with geographic restrictions'");

        // Eliminar la columna "autovalidate_sales"
        Schema::table('datasets', function (Blueprint $table) {
            $table->dropColumn('autovalidate_sales');
        });

        // Modificar la columna "type" para volver a sus valores originales
        DB::statement("ALTER TABLE datasets MODIFY COLUMN type ENUM('buyout', 'rental') DEFAULT NULL");

        // Modificar la columna "license" para volver a sus valores originales
        DB::statement("ALTER TABLE datasets MODIFY COLUMN license ENUM('national', 'european', 'unlimited') DEFAULT NULL");
    }
}
