<?php

namespace App\Http\Controllers\PSystem;

use App\Models\Material;
use App\Library\Utility;
use App\Models\Order;
use App\Models\OrderItems;
use App\User;
use Illuminate\Http\Request;


class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = Order::where('customer_id', \Auth::id())->paginate(4);
        return view('psystem.orders.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Order();
        $user = User::find(\Auth::id());

        $materials = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            ->leftJoin(\DB::raw('(SELECT order_items.material_id, sum(order_items.count) cnt
                    FROM `orders` 
                    left join order_items on orders.order_id = order_items.order_id
                    WHERE orders.object_id=1 and orders.status=4 GROUP by order_items.material_id) mat'),
              'mat.material_id', '=', 'materials2objects.material_id')
            ->where('materials2objects.object_id', $user->object_id)
            ->select('materials.title', 'materials.material_id', 'materials2objects.units', 'materials2objects.count', 'mat.cnt')
            ->get();

        return view('psystem.orders.create', compact('item', 'materials'));
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
        $user = User::find(\Auth::id());

        \DB::beginTransaction();
        $order = new Order([
            'customer_id' => $user->id,
            'object_id' => $user->object_id,
            //'object_id' => 1,
            'notes' => $data['notes'],
            'status' => 1
        ]);
        $order->save();

        $orderId = $order->order_id;
        //save materials
        if ($orderId) {
            $status = true;
            foreach ($data['material'] As $key => $val) {

                $itemOrder = new OrderItems([
                    'order_id' => $orderId,
                    'material_id' => $val,
                    'count' => $data['count'][$key],
                ]);
                $itemOrder->save();

                $status = $itemOrder ? true : false;
            }

        } else {
            $status = false;
        }

        if($status) {
            \DB::commit();
            return redirect()
                ->route('order.edit', $orderId)
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

        $order = Order::find($id);

        if($order->status === 1) //Заявку можно редактировать со статусом - создана
        {
            $order = \DB::table('orders')
                ->leftJoin('objects', 'orders.object_id', '=', 'objects.object_id')
                //->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
                ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
                ->where('order_id', $id)
                ->select('orders.*', 'objects.title', 'users.post', 'users.last_name', 'users.name', 'users.middle_name', 'users.phone')
                ->first();


            $orderMaterials = \DB::table('order_items')
                ->leftJoin('materials', 'materials.material_id', '=', 'order_items.material_id')
                ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
                ->where('order_items.order_id', $id)
                ->select('materials.title', 'order_items.count', 'order_items.id',  /*'materials2objects.units',*/ 'materials.material_id' )
                ->groupBy('id')
                ->get();

            $materials = \DB::table('materials')
                ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
                //->where('materials2objects.object_id', $order->object_id)
                ->select('materials.title', 'materials.material_id', 'materials2objects.units')
                ->get();

            return view('psystem.orders.edit', compact('order', 'orderMaterials', 'materials'));
        }

        $materials = \DB::table('order_items')
            ->leftJoin('materials', 'materials.material_id', '=', 'order_items.material_id')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            ->where('order_items.order_id', $id)
            ->select('materials.title',
                'order_items.count',
                'materials2objects.units'
            )
            ->get();

        return view('psystem.orders.preview', compact('order','materials'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
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
                'status' => 1
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
                ->route('order.edit', $orderId)
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
