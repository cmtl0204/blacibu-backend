<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthUsersTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('users', function (Blueprint $table) {
            $table->id();

            $table->integer('lang_id')
                ->nullable();

            $table->integer('ethnic_origin_id')
                ->nullable();

            $table->integer('address_id')
                ->nullable()
                ->constrained('app.address');

            $table->integer('identification_type_id')
                ->nullable();

            $table->integer('sex_id')
                ->nullable();

            $table->integer('gender_id')
                ->nullable();

            $table->integer('status_id')
                ->nullable();

            $table->integer('blood_type_id')
                ->nullable();

            $table->integer('civil_status_id')
                ->nullable();

            $table->integer('title_id')
                ->nullable();

            $table->string('avatar')
                ->nullable()
                ->unique();

            $table->string('security_image')
                ->nullable();

            $table->string('username')
                ->unique();

            $table->string('identification')
                ->unique();

            $table->string('name')
                ->nullable();

            $table->string('lastname')
                ->nullable();

            $table->string('personal_email')
                ->nullable()->unique();

            $table->date('birthdate')
                ->nullable();

            $table->string('email')
                ->unique();

            $table->string('phone')
                ->nullable();

            $table->timestamp('email_verified_at')
                ->nullable();

            $table->string('password')
                ->nullable();

            $table->boolean('is_changed_password')
                ->default(false);

            $table->integer('attempts')
                ->default(\App\Models\Authentication\User::ATTEMPTS);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('users');
    }
}
