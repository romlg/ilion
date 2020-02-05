<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SpecUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('spec_units')) {
            Schema::create('spec_units', function (Blueprint $table) {
                $table->increments('sunit_id');
                $table->integer('spec_id');
                $table->integer('n_id');
                $table->integer('count');
                $table->integer('ver');
                $table->tinyInteger('is_active');
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
        Schema::dropIfExists('spec_units');
    }
}
