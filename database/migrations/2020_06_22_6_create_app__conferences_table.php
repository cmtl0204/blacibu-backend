<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppConferencesTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION_APP'))->create('conferences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('professional_id')
                ->nullable()
                ->constrained('app.professionals');

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('app.status');

            $table->foreignId('type_id')
                ->nullable()
                ->constrained('app.catalogues');

            $table->string('modality')
                ->nullable();

            $table->string('name')
                ->nullable();

            $table->string('event')
                ->nullable();

            $table->integer('years')
                ->nullable();

            $table->string('postition')
                ->nullable();

            $table->string('category')
                ->nullable();

            $table->string('association')
                ->nullable();

            $table->string('indexed_journal')
                ->nullable();

            $table->string('function')
                ->nullable();

            $table->string('observation')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION_APP'))->dropIfExists('conferences');
    }
}
