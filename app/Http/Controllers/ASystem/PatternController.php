<?php

namespace App\Http\Controllers\ASystem;

use App\Http\Controllers\ASystem\BaseController;
use App\Models\Material;
use App\Models\Nomenclature;
use App\Models\Pattern;
use App\Models\PatternAdditionalMaterials;
use App\Models\PatternNomenclatures;
use App\Models\PatternWorks;
use App\Models\Work;
use Illuminate\Http\Request;

class PatternController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paginator =  Pattern::paginate(4);
        return view('asystem.patterns.index' , compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $nomenclatures = Nomenclature::all();
        $works = Work::all();
        $materials = Material::all();

        return view('asystem.patterns.create', compact('item', 'nomenclatures', 'works', 'materials'));
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
        $data = $request->input();

        //dd($data);

        $itemPattern = new Pattern($data);
        $itemPattern->save();

        PatternNomenclatures::insert(['pattern_id' => $itemPattern->pattern_id, 'n_id' => $data['nomenclatures'][0]]);
        PatternWorks::insert(['pattern_id' => $itemPattern->pattern_id, 'work_id' => $data['works'][0], 'count' => $data['workCount']]);
        PatternAdditionalMaterials::insert(['pattern_id' => $itemPattern->pattern_id, 'material_id' => $data['material'][0], 'count' => $data['materialCount']]);

        if($itemPattern) {
            return redirect()
                ->route('pattern.edit', $itemPattern->pattern_id)
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
        $item = Pattern::findOrFail($id);

        $works = Work::all();
        $materials = Material::all();
        $nomenclatures = Nomenclature::all();

        return view('asystem.patterns.edit' , compact('item', 'nomenclatures', 'works', 'materials'));
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
        $item = Pattern::find($id);

        $data = $request->all();
        $result = $item
            ->fill($data)
            ->save();

        if ($result) {
            return redirect()
                ->route('pattern.edit', $item->pattern_id)
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
}
