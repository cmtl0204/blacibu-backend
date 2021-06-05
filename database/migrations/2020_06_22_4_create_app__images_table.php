<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppImagesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-app')->create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('extension');
            $table->text('directory')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-app')->dropIfExists('images');
    }
}
