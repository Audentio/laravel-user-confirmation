<?php

use Audentio\LaravelBase\Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_confirmations', function (Blueprint $table) {
            $table->id();
            $table->remoteId('user_id');
            $table->string('handler_class');
            $table->string('token');
            $table->json('data');
            $table->timestamps();

            $table->unique(['user_id', 'handler_class']);
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_confirmations');
    }
}
