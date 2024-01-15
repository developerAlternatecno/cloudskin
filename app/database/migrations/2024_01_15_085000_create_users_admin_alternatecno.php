<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateUsersAdminAlternatecno extends Migration
{
    public function up()
    {
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
