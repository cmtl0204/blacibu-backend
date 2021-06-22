<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppStatusablesTable extends Migration
{

    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('statusables', function (Blueprint $table) {
            $table->id();

            $table->integer('status_id');

            $table->morphs('statusable');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('statusables');
    }
}
