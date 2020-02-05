<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Works extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('works')) {
            Schema::create('works', function (Blueprint $table) {
                $table->increments('work_id');
                $table->text('title');
                $table->string('units');
                $table->double('wtime');
                $table->double('wprice');
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
        Schema::dropIfExists('works');
    }
}
