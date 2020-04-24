<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LayoutMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('layout_materials')) {
            Schema::create('layout_materials', function (Blueprint $table) {
                $table->increments('layout_material_id');
                $table->integer('position_id');
                $table->enum('type', ['work', 'material', 'pattern']);
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
        Schema::dropIfExists('layout_materials');
    }
}
