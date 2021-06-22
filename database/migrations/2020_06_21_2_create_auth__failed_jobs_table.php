<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthFailedJobsTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('failed_jobs');
    }
}
