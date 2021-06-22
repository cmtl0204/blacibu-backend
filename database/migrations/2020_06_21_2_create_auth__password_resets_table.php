<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthPasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('password_resets', function (Blueprint $table) {
            $table->id();

            $table->string('username');

            $table->string('token')
                ->index();

            $table->boolean('is_valid')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('password_resets');
    }
}
