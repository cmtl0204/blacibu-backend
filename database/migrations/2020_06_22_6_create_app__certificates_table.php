<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCertificatesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-app')->create('certificates', function (Blueprint $table) {
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

                $table->string('hours')
                ->nullable();

            $table->string('postition')
                ->nullable();

            $table->integer('years')
                ->nullable();

            $table->string('institution_endorse')
                ->nullable();

            $table->string('indexed_journal')
                ->nullable();

            $table->string('in_quality')
                ->nullable();

            $table->string('observations')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-app')->dropIfExists('certificates');
    }
}
