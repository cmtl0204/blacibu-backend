<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppPaymentsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-app')->create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('professional_id')
                ->nullable()
                ->constrained('app.professionals');

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('app.status');

            $table->string('bank');

            $table->date('date')
                ->nullable();

            $table->string('transfer_number')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-app')->dropIfExists('payments');
    }
}
