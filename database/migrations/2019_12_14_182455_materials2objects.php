<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Materials2objects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('materials2objects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('material_id');
            $table->integer('object_id');
            $table->decimal('purchase_price');
            $table->decimal('sale_price');
            $table->integer('count');
            $table->string('units', 255);
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
        //
        Schema::dropIfExists('materials2objects');
    }
}
