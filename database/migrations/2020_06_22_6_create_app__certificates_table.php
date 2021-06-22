<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCertificatesTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('certificates', function (Blueprint $table) {
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

            $table->string('observation')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('certificates');
    }
}
