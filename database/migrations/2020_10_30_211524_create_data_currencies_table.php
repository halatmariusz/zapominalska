<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('currency_id');
            $table->string('type')->nullable();
            $table->double('value', 8, 2)->nullable();
            $table->datetime('update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_currencies');
    }
}
