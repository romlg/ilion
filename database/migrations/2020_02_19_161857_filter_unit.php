<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FilterUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('filter_unit')) {
            Schema::create('filter_unit', function (Blueprint $table) {
                $table->increments('funit_id');
                $table->integer('filter_id');
                $table->integer('material_id');
                $table->integer('count');
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
        Schema::dropIfExists('filter_unit');
    }
}
