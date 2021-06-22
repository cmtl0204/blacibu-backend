<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppDocumentsTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('documents', function (Blueprint $table) {
            $table->id();

            $table->integer('professional_id')
                ->nullable()
                ->constrained('app.professionals');

            $table->integer('status_id')
                ->nullable()
                ;

            $table->integer('type_id')
                ->nullable()
                ;

            $table->text('aditional_information')
                ->nullable();
            $table->string('observation')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('documents');
    }
}
