<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('length');
            $table->date('dateTime');
            $table->integer('user_id')->unsigned();
            $table->integer('payment_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

        $table->foreign('payment_id')
            ->references('id')
            ->on('dates')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dates');
    }
}
