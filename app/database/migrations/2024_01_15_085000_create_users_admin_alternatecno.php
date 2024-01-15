<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Asegúrate de importar el modelo User si no está

class CreateUsersAdminAlternatecno extends Migration
{
    public function up()
    {
        // Agregar columna o realizar otras modificaciones si es necesario

        // Insertar nuevo usuario
        User::create([
            'name' => 'Admin Alternatecno',
            'email' => 'admin@alternatecno.es',
            'password' => Hash::make('2D8kdyULu4ZrY8'),
        ]);
    }

    public function down()
    {
        // Código para revertir los cambios si es necesario
    }
}
