<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthUserSecurityQuestionTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('user_security_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('security_question_id')->constrained('authentication.security_questions');
            $table->string('answer');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('permission_role');
    }
}
