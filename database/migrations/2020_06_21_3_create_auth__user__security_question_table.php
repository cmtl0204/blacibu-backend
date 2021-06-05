<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthUserSecurityQuestionTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-authentication')->create('user_security_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('authentication.users');
            $table->foreignId('security_question_id')->constrained('authentication.security_questions');
            $table->string('answer');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-authentication')->dropIfExists('permission_role');
    }
}
