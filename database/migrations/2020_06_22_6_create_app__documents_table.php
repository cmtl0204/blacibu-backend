<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppDocumentsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-app')->create('documents', function (Blueprint $table) {
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
        Schema::connection('pgsql-app')->dropIfExists('documents');
    }
}
