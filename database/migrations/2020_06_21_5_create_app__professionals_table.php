<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppProfessionalsTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('professionals', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id');

            $table->integer('status_id')
                ->nullable();

            $table->integer('country_id')
                ->nullable()
                ->constrained('app.locations');

            $table->string('membership_number')
                ->nullable();

            $table->integer('certified_date')
                ->nullable();

            $table->string('degree_time')
                ->nullable();

            $table->integer('years_graduated')
                ->nullable();

            $table->string('nationality')
                ->nullable();

            $table->string('observation')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('professionals');
    }
}
