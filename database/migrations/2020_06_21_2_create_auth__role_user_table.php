<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthRoleUserTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('role_user', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id');

            $table->integer('role_id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('role_user');
    }
}
