<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Nomenclatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('nomenclatures')) {
            Schema::create('nomenclatures', function (Blueprint $table) {
                $table->increments('n_id');
                $table->text('title');
                $table->integer('group_id')->nullable();
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
        Schema::dropIfExists('nomenclatures');
    }
}
