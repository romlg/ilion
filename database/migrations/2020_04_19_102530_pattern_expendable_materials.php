<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatternExpendableMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('pattern_expendable_materials')) {
            Schema::create('pattern_expendable_materials', function (Blueprint $table) {
                $table->increments('pem_id');
                $table->integer('pattern_id');
                $table->integer('material_id');
                $table->integer('count');
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
        Schema::dropIfExists('pattern_expendable_materials');

    }
}
