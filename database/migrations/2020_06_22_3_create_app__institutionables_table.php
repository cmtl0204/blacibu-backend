<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppInstitutionablesTable extends Migration
{

    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('institutionables', function (Blueprint $table) {
            $table->id();
            $table->integer('institution_id');
            $table->morphs('institutionable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('institutionables');
    }
}
