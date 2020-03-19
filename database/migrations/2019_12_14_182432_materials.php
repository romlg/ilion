<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Materials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('materials')) {
            Schema::create('materials', function (Blueprint $table) {
                $table->increments('material_id');
                $table->text('title');
                $table->string('vendor_code', 100);
                $table->integer('unit');
                $table->text('notes')->nullable();
                $table->integer('producer_id')->nullable();
                $table->integer('pattern_material_id')->nullable();
                $table->tinyInteger('category_id')->nullable();
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
        Schema::dropIfExists('materials');
    }
}
