<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AlterTableUserRole extends Migration
{
    public function up()
    {
        // Insertar los roles iniciales
        Role::create(['name' => 'Admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'User'])
            ->givePermissionTo(Permission::all());

        // Modificar la tabla 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned()->nullable()->default(DB::table('roles')->where('name', 'user')->value('id'))->after('remember_token');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
        });

    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
}
