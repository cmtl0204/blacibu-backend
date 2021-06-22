<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppFilesTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION_APP'))->create('files', function (Blueprint $table) {
            $table->id();
            $table->morphs('fileable');
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
        Schema::connection(env('DB_CONNECTION_APP'))->dropIfExists('files');
    }
}
