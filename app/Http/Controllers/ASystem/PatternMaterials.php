<?php

namespace App\Http\Controllers\ASystem;

use App\Helpers\Func\Func;
use App\Http\Controllers\ASystem\BaseController;
//use App\Models\Filter;
//use App\Models\FilterUnit;

use App\Models\PatternMaterials as PM;
//use App\Models\Material;
use Illuminate\Http\Request;

class PatternMaterials extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator =  PM::paginate(4);
        return view('asystem.pattern_materials.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $units = config('units');
        return view('asystem.pattern_materials.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'title' => 'required|min:2|max:255',
            'unit' => 'required'
        ]);

        $data = $request->input();

        $item = new PM($data);
        $item->save();

        if($item) {
            return redirect()
                ->route('patternMaterials.edit', $item->pattern_material_id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
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
        $item = PM::findOrFail($id);
        $units = config('units');
        return view('asystem.pattern_materials.edit', compact('item', 'units'));
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
        $validatedData = $request->validate([
            'title' => 'required|min:2|max:255',
            'unit' => 'required'
        ]);

        $item = PM::find($id);

        $data = $request->all();
        $result = $item
            ->fill($data)
            ->save();

        if ($result) {
            return redirect()
                ->route('patternMaterials.edit', $item->pattern_material_id )
                ->with(['success' => "Успешно сохранено"]);
        } else {
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

//    public function copy(Request $request)
//    {
//        $data = $request->all();
//
//        if(!isset($data['filter'])) {
//            return back()
//                ->withErrors(['msg' => "Фильтр не выбран"])
//                ->withInput();
//        }
//
//        foreach ($data['filter'] as $filterId) {
//
//            $filterCopy = Filter::find($filterId);
//
//            $filter = new Filter();
//            $filter->title = $filterCopy->title . ' copy';
//            $filter->save();
//
//            $filterMaterials = FilterUnit::where('filter_id', $filterId)->get();
//            foreach ( $filterMaterials as $filterMaterial) {
//                FilterUnit::insert(['filter_id' => $filter->filter_id, 'material_id' => $filterMaterial->material_id, 'count' => $filterMaterial->count]);
//            }
//        }
//
//        return redirect()
//            ->route('filter.index')
//            ->with(['success' => "Фильтры успешно скопированы"]);
//
//    }
}
