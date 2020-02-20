<?php

namespace App\Http\Controllers\ASystem;

use App\Helpers\Func\Func;
use App\Http\Controllers\ASystem\BaseController;
use App\Models\Filter;
use App\Models\FilterUnit;
use App\Models\Material;
use Illuminate\Http\Request;

class FiltersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator =  Filter::paginate(4);
        return view('asystem.filters.index' , compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $materials = Material::all();
        return view('asystem.filters.create', compact('materials'));
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
            'title' => 'required|min:2|max:255'
        ]);

        $data = $request->input();

        if(Func::array_has_dupes($data['material'])) {
            return redirect()
                ->route('filter.create')
                ->with(['error' => "Ошибка сохранения. Присутствуют дубликаты"]);
        }

        $itemFilter = new Filter($data);
        $itemFilter->save();

        foreach ($data['material'] as $key => $material) {
            FilterUnit::insert(['filter_id' => $itemFilter->filter_id, 'material_id' => $material, 'count' => $data['materialCount'][$key]]);
        }

        if($itemFilter) {
            return redirect()
                ->route('filter.edit', $itemFilter->filter_id)
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

        $item = Filter::findOrFail($id);
        $materials = Material::all();

        return view('asystem.filters.edit', compact('item', 'materials'));
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
            'title' => 'required|min:2|max:255'
        ]);

        $data = $request->all();

        if(Func::array_has_dupes($data['material'])) {
            return redirect()
                ->route('filter.edit', $id)
                ->with(['error' => "Ошибка сохранения. Присутствуют дубликаты"]);
        }

        $itemFilter = Filter::find($id);
        $result = $itemFilter
            ->fill($data)
            ->save();

        FilterUnit::where('filter_id', $id)->delete();
        foreach ($data['material'] as $key => $material) {
            FilterUnit::insert(['filter_id' => $itemFilter->filter_id, 'material_id' => $material, 'count' => $data['materialCount'][$key]]);
        }

        if ($result) {
            return redirect()
                ->route('filter.edit', $itemFilter->filter_id)
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

    public function copy(Request $request)
    {
        $data = $request->all();

        if(!isset($data['filter'])) {
            return back()
                ->withErrors(['msg' => "Фильтр не выбран"])
                ->withInput();
        }

        foreach ($data['filter'] as $filterId) {

            $filterCopy = Filter::find($filterId);

            $filter = new Filter();
            $filter->title = $filterCopy->title . ' copy';
            $filter->save();

            $filterMaterials = FilterUnit::where('filter_id', $filterId)->get();
            foreach ( $filterMaterials as $filterMaterial) {
                FilterUnit::insert(['filter_id' => $filter->filter_id, 'material_id' => $filterMaterial->material_id, 'count' => $filterMaterial->count]);
            }
        }

        return redirect()
            ->route('filter.index')
            ->with(['success' => "Успешно скопированы"]);

    }
}
