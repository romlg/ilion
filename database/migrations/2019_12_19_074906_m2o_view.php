<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M2oView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //
        DB::statement("CREATE OR REPLACE VIEW ".getenv('DB_DATABASE').".m2o_view AS
                                select 
                                    o.object_id as o_id, 
                                    o.title as o_title, 
                                    s.stage_id as s_id, 
                                    s.title as s_title, 
                                    s.ver as s_ver, 
                                    m2o.material_id as m_id, 
                                    m.title as m_title, 
                                    m2o.purchase_price as m_purchase_price, 
                                    m2o.price as m_price, 
                                    m2o.count as m_count, 
                                    m2o.units as m_units, 
                                    m2o.work_price as m_work_price
                                from ilion_new.objects as o
                                    left join ilion_new.stages as s on s.object_id=o.object_id
                                    left join ilion_new.materials2objects as m2o on s.stage_id=m2o.stage_id
                                    left join ilion_new.materials as m on m.material_id=m2o.material_id
                                    where o.is_active=1 And m2o.is_active=1"
                     );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('m2o_view');
    }
}
