<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class AlterTableUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password_confirmation', 255)->nullable()->after('password');
            $table->string('nationality', 30)->nullable()->after('password_confirmation');
            //$table->string('nif', 9)->nullable()->after('nationality');
            $table->string('documento_identidad', 250)->nullable()->after('nif');
            
        });

        User::whereNull('nationality')->update(['nationality' => null]);
        User::whereNull('nif')->update(['nif' => null]);
        User::whereNull('documento_identidad')->update(['documento_identidad' => null]);

    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nationality');
            ///$table->dropColumn('nif');
            $table->dropColumn('documento_identidad');
        });
    }
}