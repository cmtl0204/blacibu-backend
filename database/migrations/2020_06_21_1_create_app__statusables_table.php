<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppStatusablesTable extends Migration
{

    public function up()
    {
        Schema::connection(env('DB_CONNECTION_APP'))->create('statusables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('status_id')
                ->constrained('app.status');

            $table->morphs('statusable');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION_APP'))->dropIfExists('statusables');
    }
}
