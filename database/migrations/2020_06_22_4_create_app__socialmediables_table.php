<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSocialmediablesTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('socialmediables', function (Blueprint $table) {
            $table->id();
            $table->integer('socialmedia_id')->constrained('app.socialmedia');
            $table->morphs('socialmediable');
            $table->string('user')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('socialmediables');
    }
}
