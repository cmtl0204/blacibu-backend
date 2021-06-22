<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthPermissionRoleTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('permission_role', function (Blueprint $table) {
            $table->id();

            $table->integer('role_id');

            $table->integer('permission_id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('permission_role');
    }
}
