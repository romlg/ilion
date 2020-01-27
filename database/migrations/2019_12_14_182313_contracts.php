<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('contracts')) {
            Schema::create('contracts', function (Blueprint $table) {
                $table->increments('contract_id');
                $table->integer('contract_date');
                $table->string('title', 255)->nullable();
                $table->text('text')->nullable();
                $table->tinyInteger('is_signed');
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
        Schema::dropIfExists('contracts');
    }
}
