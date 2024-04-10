<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateUsersAdminAlternatecno extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password_confirmation', 255)->nullable()->after('password');
            $table->string('documento_identidad', 250)->nullable()->after('nif');
            
        });

        User::create([
            'name' => 'Admin Alternatecno',
            'email' => 'admin@alternatecno.es',
            'password' => Hash::make('2D8kdyULu4ZrY8'),
            'nif'      => '00000000A'
        ]);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_confirmation');
            $table->dropColumn('documento_identidad');
        });
    }
}
