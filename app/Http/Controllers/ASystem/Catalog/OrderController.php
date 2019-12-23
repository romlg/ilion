<?php

namespace App\Http\Controllers\ASystem\Catalog;

use App\Library\Utility;
use App\Models\Objct;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends CatalogController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = isset($_GET['status']) ? $_GET['status'] : 1;

        $paginator = Order::query()
            ->leftJoin('objects', 'orders.object_id', '=', 'objects.object_id')
            ->where('status', $status)
            ->orderBy('orders.created_at', 'asc')->paginate(4);

        $filterStatus = Utility::orderStatus;

        return view('asystem.orders.index', compact('paginator', 'filterStatus', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function step1(Request $request)
    {
        $objects = Objct::query()
            ->select('object_id', 'title')
            ->orderBy('title', 'asc')
            ->get();

        return view('asystem.orders.precreate', compact('objects'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!isset($_GET['object_id']) || !$_GET['object_id']) {
            return back()
                ->withErrors(['msg' => "Сначала выберите объект для которого будет создана заявка"]);
        }
        $object_id = $_GET['object_id'];

        $object = Objct::findOrFail($object_id);
        $order = new Order();

        $materials = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            //->where('materials2objects.stage_id', $object_id)
            ->select('materials.title', 'materials.material_id', 'materials2objects.units')
            ->get();

        $materials = $materials->unique();

        return view('asystem.orders.edit', compact('object', 'materials', 'order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();

        \DB::beginTransaction();

        $order = new Order([
            'customer_id' => Auth::id(), /// вот тут текущий пользователь
            'object_id' => $data['object_id'],
            'notes' => $data['notes'],
            'status' => 1
        ]);
        $order->save();

        $orderId = $order->order_id;

        //save materials
        if ($orderId) {

            $status = true;
            foreach ($data['material'] As $key => $val) {

                if ($data['count'][$key]) {
                    $itemOrder = new OrderItems([
                        'order_id' => $orderId,
                        'material_id' => $val,
                        'count' => $data['count'][$key],
                    ]);
                    $itemOrder->save();
                }

                $status = $itemOrder ? true : false;
            }

        } else {
            $status = false;
        }

        if($status) {
            \DB::commit();
            return redirect()
                ->route('aorder.edit', $orderId)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            \DB::rollBack();
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = \DB::table('orders')
            ->leftJoin('objects', 'orders.object_id', '=', 'objects.object_id')
            //->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
            ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
            ->where('order_id', $id)
            //->select('orders.*', 'objects.title', 'customers.post', 'customers.last_name', 'customers.first_name', 'customers.middle_name', 'customers.phone')
            ->select('orders.*', 'objects.title', 'users.post', 'users.last_name', 'users.name', 'users.middle_name', 'users.phone')
            ->first();

        //тут проверка что существует заявка и пользователь совпадает
        $orderMaterials = \DB::table('order_items')
            ->where('order_items.order_id', $id)
            ->select('order_items.count', 'order_items.material_id')
            ->get();

        $materials = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            //->where('materials2objects.object_id', $order->object_id)
            ->select('materials.title', 'materials.material_id', 'materials2objects.units')
            ->get();

        $materials = $materials->unique();

        $filterStatus = Utility::orderStatus;

        return view('asystem.orders.edit', compact('order', 'orderMaterials', 'materials', 'filterStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->input();

        \DB::beginTransaction();

        $order = Order::find($id);
        $result = $order
            ->fill([
                'notes' => $data['notes'],
                'status' => $data['status']
            ])
            ->save();

        $orderId = $id;

        //save materials
        if ($orderId && $result) {

            //удалить старые записи из OrderItems
            \DB::table('order_items')->where('order_id', '=', $orderId)->delete();

            $status = true;
            foreach ($data['material'] As $key => $val) {

                if ($data['count'][$key]) {
                    $itemOrder = new OrderItems([
                        'order_id' => $orderId,
                        'material_id' => $val,
                        'count' => $data['count'][$key],
                    ]);
                    $itemOrder->save();
                }

                $status = $itemOrder ? true : false;
            }

        } else {
            $status = false;
        }

        if($status) {
            \DB::commit();
            return redirect()
                ->route('aorder.edit', $orderId)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            \DB::rollBack();
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
