<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatternPrices extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('pattern_prices')) {
            Schema::create('pattern_prices', function (Blueprint $table) {
                $table->increments('pattern_price_id');
                $table->text('title');
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
        Schema::dropIfExists('pattern_prices');
    }
}
