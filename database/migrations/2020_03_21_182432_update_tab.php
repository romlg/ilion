<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        if (Schema::hasColumn('materials', 'vendor_code')) {
//            Schema::table('materials', function (Blueprint $table) {
//                $table->string('vendor_code', 100);
//            });
//        }
//        if (Schema::hasColumn('materials', 'unit')) {
//            Schema::table('materials', function (Blueprint $table) {
//                $table->integer('unit');
//            });
//        }
//        if (Schema::hasColumn('materials', 'producer_id')) {
//            Schema::table('materials', function (Blueprint $table) {
//                $table->integer('producer_id')->nullable();
//            });
//        }
//        if (Schema::hasColumn('materials', 'pattern_material_id')) {
//            Schema::table('materials', function (Blueprint $table) {
//                $table->integer('pattern_material_id')->nullable();
//            });
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('materials');
    }
}
