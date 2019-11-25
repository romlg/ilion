<?php

namespace App\Http\Controllers\PSystem;

use App\Models\Material;
use App\Library\Utility;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;


class OrderController extends BaseController
{
    var $user = 1; //потом удалить
    var $object_id = 1; //потом удалить

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = Order::where('customer_id', $this->user)->paginate(4);
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
//\DB::enableQueryLog();
        $materials = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            ->leftJoin(\DB::raw('(SELECT order_items.material_id, sum(order_items.count) cnt
                    FROM `orders` 
                    left join order_items on orders.order_id = order_items.order_id
                    WHERE orders.object_id=1 and orders.status=4 GROUP by order_items.material_id) mat'),
              'mat.material_id', '=', 'materials2objects.material_id')
            ->where('materials2objects.object_id', $this->object_id)
            ->select('materials.title', 'materials.material_id', 'materials2objects.units', 'materials2objects.count', 'mat.cnt')
            ->get();
//       dd($materials);
//        dd(\DB::getQueryLog());

        return view('psystem.orders.edit', compact('item', 'materials'));
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
            'customer_id' => $this->user,
            'object_id' => $this->object_id,
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
        //тут проверка что существует заявка и пользователь совпадает

        $materials = \DB::table('order_items')
            ->leftJoin('materials', 'materials.material_id', '=', 'order_items.material_id')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            ->where('order_items.order_id', $id)
            ->select('materials.title',
                'order_items.count',
                'materials2objects.units'
            )
            ->get();
         //dd($materials);
//dd(\DB::getQueryLog());
        return view('psystem.orders.preview', compact('order','materials'));
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
        //
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
