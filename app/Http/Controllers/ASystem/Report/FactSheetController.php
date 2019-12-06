<?php

namespace App\Http\Controllers\ASystem\Report;

use App\Models\Objct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FactSheetController extends ReportController
{
    /*
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {

        $object_id = 1; // выбирать из списка

        $object = Objct::findOrFail($object_id);

        $materials = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            ->where('materials2objects.object_id', $object_id)
            ->select('materials.title', 'materials2objects.count', 'materials2objects.units', 'materials.material_id')
            ->get();


        $orders = \DB::table('orders')
            ->leftJoin('order_items', 'orders.order_id', '=', 'order_items.order_id')
            ->where('orders.object_id', $object_id)
            ->where('orders.status', 4)
            ->select('order_items.material_id', 'order_items.count')
            ->get();

        $orderMaterial = [];
        foreach ($orders AS $order) {
            if (isset($orderMaterial[$order->material_id])) {
                $orderMaterial[$order->material_id] += (int)$order->count;
            } else {
                $orderMaterial[$order->material_id] = (int)$order->count;
            }
        }

        $facts = \DB::table('facts')
            ->leftJoin('fact_items', 'facts.fact_id', '=', 'fact_items.fact_id')
            ->where('facts.object_id', $object_id)
            ->select('fact_items.material_id', 'fact_items.count')
            ->get();

        $factMaterial = [];
        foreach ($facts AS $fact) {
            if (isset($factMaterial[$fact->material_id])) {
                $factMaterial[$fact->material_id] += (int)$fact->count;
            } else {
                $factMaterial[$fact->material_id] = (int)$fact->count;
            }
        }

        $data = [];
        foreach ($materials AS $material) {
            $data[] = [
                'material_id' => $material->material_id,
                'title' => $material->title,
                'count' => $material->count,
                'units' => $material->units,

                'delivered' => isset($orderMaterial[$material->material_id]) ? $orderMaterial[$material->material_id] :  '',
                'processed' => isset($factMaterial[$material->material_id]) ? $factMaterial[$material->material_id] :  '',
            ];
        }

       // dd($data);
        return view('asystem.factsheet.table', compact('data'));
    }

}
