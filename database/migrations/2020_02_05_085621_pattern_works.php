<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatternWorks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('pattern_works')) {
            Schema::create('pattern_works', function (Blueprint $table) {
                $table->increments('pworks_id');
                $table->integer('pattern_id');
                $table->integer('work_id');
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
        Schema::dropIfExists('pattern_works');
    }
}
