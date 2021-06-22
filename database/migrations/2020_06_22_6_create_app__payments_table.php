<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppPaymentsTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('payments', function (Blueprint $table) {
            $table->id();

            $table->integer('professional_id')
                ->nullable()
                ->constrained('app.professionals');

            $table->integer('status_id')
                ->nullable()
                ;

            $table->string('bank')
                ->nullable();

            $table->date('date')
                ->nullable();

            $table->string('transfer_number')
                ->nullable();

            $table->string('observation')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('payments');
    }
}
