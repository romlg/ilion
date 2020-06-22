<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Prices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('prices')) {
            Schema::create('prices', function (Blueprint $table) {
                $table->increments('price_id');
                $table->integer('material_id');
                $table->double('sprice')->nullable();
                $table->double('oprice')->nullable();
                $table->double('price')->nullable();
                $table->integer('user_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('prices');
    }
}
