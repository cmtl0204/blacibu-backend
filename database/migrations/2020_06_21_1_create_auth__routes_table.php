<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthRoutesTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('routes', function (Blueprint $table) {
            $table->id();

            $table->integer('parent_id')->nullable()
                ->comment('Una ruta puede tener rutas hijas');

            $table->integer('module_id')
                ->comment('Cada ruta debe pertenecer a un modulo del sistema');

            $table->integer('type_id')
                ->comment('Tipo de ruta: megamenu, menu normal');

            $table->integer('status_id')
                ->comment('Para saber si la ruta esta disponible o en mantenimiento');

            $table->string('uri')
                ->comment('La direccion de la ruta en el frontend');

            $table->string('name')
                ->comment('Nombre de la ruta');

            $table->text('description')
                ->comment('Descripcion de la ruta');

            $table->string('icon')
                ->nullable()
                ->comment('Icono de la libreria que se usa en el frontend');

            $table->string('logo');

            $table->integer('order')
                ->comment('Orden que apareceran las rutas en la interfaz');

            $table->boolean('is_link')
                ->default(true)
                ->comment('Si la ruta es link o no');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('routes');
    }
}
