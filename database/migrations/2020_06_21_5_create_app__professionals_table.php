<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppProfessionalsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-app')->create('professionals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('authentication.users');

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('app.status');

            $table->foreignId('country_id')
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

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-app')->dropIfExists('professionals');
    }
}
