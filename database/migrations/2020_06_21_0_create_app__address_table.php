<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppAddressTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION_APP'))->create('address', function (Blueprint $table) {
            $table->id();

            $table->foreignId('location_id')
                ->nullable()
                ->constrained('app.locations');

            $table->string('main_street')->nullable();

            $table->string('secondary_street')->nullable();

            $table->string('number')
                ->nullable()
                ->comment('número de casa');

            $table->string('post_code')
                ->nullable()
                ->comment('código postal');

            $table->text('reference')
                ->nullable();

            $table->double('latitude')
                ->nullable();

            $table->double('longitude')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION_APP'))->dropIfExists('address');
    }

}
