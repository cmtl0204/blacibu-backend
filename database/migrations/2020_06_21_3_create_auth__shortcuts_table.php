<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthShortcutsTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('shortcuts', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id');

            $table->integer('role_id')
                ->comment('Solo aparecen en el rol correspondiente');

            $table->integer('permission_id')
                ->comment('Para poder dar integridad y acceder a la ruta');

            $table->string('name');

            $table->text('description')
                ->nullable();

            $table->string('image');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('shortcuts');
    }
}
