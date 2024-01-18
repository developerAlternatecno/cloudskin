<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterTablePurchases extends Migration
{
    public function up()
    {
        // Eliminar restricción ENUM temporalmente
        DB::statement("ALTER TABLE purchases MODIFY COLUMN type VARCHAR(255) DEFAULT NULL");

        // Actualizar registros existentes para cambiar 'buyout' a 'sale' en la columna "type"
        DB::table('purchases')->where('type', 'buyout')->update(['type' => 'sale']);

        // Actualizar el tipo de columna 'type' a ENUM
        DB::statement("ALTER TABLE purchases MODIFY COLUMN type ENUM('sale', 'rental', 'free') DEFAULT NULL");
    
    }

    public function down()
    {
        DB::table('purchases')->where('type', 'sale')->update(['type' => 'buyout']);
        DB::statement("ALTER TABLE purchases MODIFY COLUMN type ENUM('buyout', 'rental') DEFAULT NULL");
    }
}
