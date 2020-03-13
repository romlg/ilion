<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatternAdditionalMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('pattern_additional_materials')) {
            Schema::create('pattern_additional_materials', function (Blueprint $table) {
                $table->increments('pam_id');
                $table->integer('pattern_id');
                $table->integer('material_id');
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
        Schema::dropIfExists('pattern_additional_materials');
    }
}
