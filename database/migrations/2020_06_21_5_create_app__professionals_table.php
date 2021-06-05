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
                ->nullable()
                ->constrained('authentication.users');

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('app.status');

            $table->foreignId('location_id')
                ->nullable()
                ->constrained('app.locations');

            $table->string('membership_number')
                ->nullable();

            $table->date('certified_date')
                ->nullable();

            $table->string('degree_time')
                ->nullable();

            $table->integer('years_graduated')
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
