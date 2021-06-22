<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthPermissionsTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('permissions', function (Blueprint $table) {
            $table->id();

            $table->integer('route_id')
                ->comment('Ruta a la que va a tener acceso el permiso');

            $table->integer('system_id')
                ->comment('Para que el permiso pertenezca a un sistema');

            $table->integer('institution_id')
                ->nullable();

            $table->string('name');
            $table->text('description')->nullable();

            $table->json('actions')
                ->comment('[INDEX, STORE, UPDATE, DESTROY], etc');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('permissions');
    }
}
