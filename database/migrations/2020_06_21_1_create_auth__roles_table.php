<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthRolesTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('roles', function (Blueprint $table) {
            $table->id();

            $table->integer('system_id')
                ->comment('Para que el rol pertenezca a un sistema');

            $table->integer('institution_id')
                ->nullable();

            $table->string('code')
                ->comment('No debe ser modificado una vez que se lo crea');

            $table->text('name');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('roles');
    }
}
