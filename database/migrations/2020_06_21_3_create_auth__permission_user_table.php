<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthPermissionUserTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('permission_user', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')
                ;

            $table->integer('permission_id')
                ;

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('permission_user');
    }
}
