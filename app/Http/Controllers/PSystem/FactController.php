<?php

namespace App\Http\Controllers\PSystem;

use App\Models\Fact;
use App\Models\FactItems;
use App\Models\Order;
use App\Models\OrderItems;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FactController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = Fact::where('customer_id', \Auth::id())->paginate(4);
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
        $user = User::find(\Auth::id());

        //\DB::enableQueryLog();
        $materials = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            ->where('materials2objects.object_id', $user->object_id)
            ->select('materials.title', 'materials.material_id', 'materials2objects.units')
            ->get();
        // dd($materials);
        // dd(\DB::getQueryLog());
        return view('psystem.facts.create', compact('item', 'materials'));
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
        $fact = new Fact([
            'customer_id' => $user->id,
            'object_id' => $user->object_id,
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fact = \DB::table('facts')
            ->where('facts.fact_id', $id)
            ->select( 'facts.fact_id', 'facts.status', 'facts.notes')
            ->first();


        $materials = \DB::table('facts')
            ->leftJoin('fact_items', 'fact_items.fact_id', '=', 'facts.fact_id')
            ->leftJoin('materials', 'materials.material_id', '=', 'fact_items.material_id')
            ->where('facts.fact_id', $id)
            //->select('facts.notes', 'fact_items.count', 'materials.title')
            ->select( 'fact_items.id', 'fact_items.count', 'materials.material_id', 'materials.title')
            ->get();

        $status = $fact->status == 1 ? 'Принятый' : 'Новый';

        return view('psystem.facts.edit', compact('fact', 'status', 'materials'));
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

        $fact = Fact::find($id);
        $fact->notes = $data['notes'];

        \DB::table('fact_items')->where('fact_id', '=', $fact['fact_id'])->delete();


        $status = true;
        foreach ($data['material_id'] as $key => $val) {

            if ($data['count'][$key]) {
                $factItems = new FactItems([
                    'fact_id' => $id,
                    'material_id' => $val,
                    'count' => $data['count'][$key],
                ]);
                $factItems->save();
            }

            $status = $factItems ? true : false;
        }

        if ($fact->save() && $status) {
            \DB::commit();
            return redirect()
                ->route('fact.edit', $id)
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
