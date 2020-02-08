<?php

namespace App\Http\Controllers\ASystem;

use App\Helpers\Func\Func;
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
        $validatedData = $request->validate([
            'title' => 'required|min:2|max:255',
            'workCount' => 'required|min:1|max:255',
            'materialCount' => 'required|min:1|max:255',
        ]);

        $data = $request->input();

        if(Func::array_has_dupes($data['nomenclatures']) || Func::array_has_dupes($data['works']) || Func::array_has_dupes($data['material'])) {
            return redirect()
                ->route('pattern.create')
                ->with(['error' => "Ошибка сохранения. Присутствуют дубликаты"]);
        }

        $itemPattern = new Pattern($data);
        $itemPattern->save();

        foreach ($data['nomenclatures'] as $nomenclature) {
            PatternNomenclatures::insert(['pattern_id' => $itemPattern->pattern_id, 'n_id' => $nomenclature]);
        }

        foreach ($data['works'] as $key => $work) {
            PatternWorks::insert(['pattern_id' => $itemPattern->pattern_id, 'work_id' => $work, 'count' => $data['workCount'][$key]]);
        }

        foreach ($data['material'] as $key => $material) {
            PatternAdditionalMaterials::insert(['pattern_id' => $itemPattern->pattern_id, 'material_id' => $material, 'count' => $data['materialCount'][$key]]);
        }

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

        return view('asystem.patterns.edit', compact('item', 'nomenclatures', 'works', 'materials'));
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
            'workCount' => 'required|min:1|max:255',
            'materialCount' => 'required|min:1|max:255',
        ]);

        $item = Pattern::find($id);

        $data = $request->all();
        $result = $item
            ->fill($data)
            ->save();

        PatternNomenclatures::where('pattern_id', $id)->delete();
        PatternNomenclatures::insert(['pattern_id' => $id, 'n_id' => $data['nomenclatures'][0]]);

        PatternWorks::where('pattern_id', $id)->delete();
        PatternWorks::insert(['pattern_id' => $id, 'work_id' => $data['works'][0], 'count' => $data['workCount']]);

        PatternAdditionalMaterials::where('pattern_id', $id)->delete();
        PatternAdditionalMaterials::insert(['pattern_id' => $id, 'material_id' => $data['material'][0], 'count' => $data['materialCount']]);

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
