<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialToLayoutPatternMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_to_layout_pattern_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('layout_material_id');
            $table->integer('material_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_to_layout_pattern_materials');
    }
}
