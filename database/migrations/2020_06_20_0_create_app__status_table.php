<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppStatusTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('status', function (Blueprint $table) {
            $table->id();

            $table->string('code')
                ->comment('1. ACTIVE, 2. INACTIVE, 3. LOCKED. etc');

            $table->string('name');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('status');
    }
}
