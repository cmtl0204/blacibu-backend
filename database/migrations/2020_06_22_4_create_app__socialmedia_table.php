<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSocialmediaTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-app')->create('socialmedia', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('logo')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-app')->dropIfExists('socialmedia');
    }
}
