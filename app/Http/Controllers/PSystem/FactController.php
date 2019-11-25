<?php

namespace App\Http\Controllers\PSystem;

use App\Models\Fact;
use App\Models\FactItems;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FactController extends BaseController
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
        $paginator = Fact::where('customer_id', $this->user)->paginate(4);
        return view('psystem.facts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Fact();
//\DB::enableQueryLog();
        $materials = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            ->where('materials2objects.object_id', $this->object_id)
            ->select('materials.title', 'materials.material_id', 'materials2objects.units')
            ->get();
        // dd($materials);
//dd(\DB::getQueryLog());
        return view('psystem.facts.edit', compact('item', 'materials'));
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
        $fact = new Fact([
            'customer_id' => $this->user,
            'object_id' => $this->object_id,
            'notes' => $data['notes']
        ]);
        $fact->save();

        $factId = $fact->fact_id;
        //save materials
        if ($factId) {
            $status = true;
            foreach ($data['material'] As $key => $val) {

                $itemFact = new FactItems([
                    'fact_id' => $factId,
                    'material_id' => $val,
                    'count' => $data['count'][$key],
                ]);
                $itemFact->save();

                $status = $itemFact ? true : false;
            }

        } else {
            $status = false;
        }

        if($status) {
            \DB::commit();
            return redirect()
                ->route('fact.edit', $factId)
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
        //
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
