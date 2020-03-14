<?php

namespace App\Http\Controllers\ASystem;

use App\Helpers\Func\Func;
use App\Http\Controllers\ASystem\BaseController;
use App\Models\Material;
use App\Models\Nomenclature;
use App\Models\PatternAdditionalMaterials;
use App\Models\PatternMaterials;
use App\Models\PatternNomenclatures;
use App\Models\PatternWorks;
use App\Models\PatternPrices;
use App\Models\Work;
use Illuminate\Http\Request;


class PatternPricesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paginator =  PatternPrices::paginate(4);
        return view('asystem.pattern_prices.index' , compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $nomenclatures = Nomenclature::active()->get();
        $works = Work::active()->get();
        $materials = PatternMaterials::all();

        return view('asystem.pattern_prices.create', compact('nomenclatures', 'works', 'materials'));
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

        if( $data['nomenclatures'] == null) {
                return redirect()
                    ->route('patternPrices.create')
                    ->with(['error' => "Ошибка сохранения. Не выбрана номенклатура"]);
        }

        foreach ($data['works'] as $work) {
            if( $work == null) {
                return redirect()
                    ->route('patternPrices.create')
                    ->with(['error' => "Ошибка сохранения. Не выбрана работа"]);
            }
        }

        if(Func::array_has_dupes($data['works']) || Func::array_has_dupes($data['material'])) {
            return redirect()
                ->route('patternPrices.create')
                ->with(['error' => "Ошибка сохранения. Присутствуют дубликаты"]);
        }

        $itemPattern = new PatternPrices($data);
        $itemPattern->save();

        PatternNomenclatures::insert(['pattern_id' => $itemPattern->pattern_price_id, 'n_id' => $data['nomenclatures']]);

        foreach ($data['works'] as $key => $work) {
            PatternWorks::insert(['pattern_id' => $itemPattern->pattern_price_id, 'work_id' => $work, 'count' => $data['workCount'][$key]]);
        }

        foreach ($data['material'] as $key => $material) {
            PatternAdditionalMaterials::insert(['pattern_id' => $itemPattern->pattern_price_id, 'material_id' => $material]);
        }

        if($itemPattern) {
            return redirect()
                ->route('patternPrices.edit', $itemPattern->pattern_price_id)
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
        $item = PatternPrices::findOrFail($id);

        $nomenclatures = Nomenclature::all();
        $works = Work::all();
        $patternMaterials = PatternMaterials::all();

        return view('asystem.pattern_prices.edit', compact('item', 'nomenclatures', 'works', 'patternMaterials'));
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
//        $validatedData = $request->validate([
//            'title' => 'required|min:2|max:255'
//        ]);
//
//        $data = $request->all();
//
//        foreach ($data['nomenclatures'] as $nomenclature) {
//            if( $nomenclature == null) {
//                return redirect()
//                    ->route('pattern.edit', $id)
//                    ->with(['error' => "Ошибка сохранения. Не выбрана номенклатура"]);
//            }
//        }
//
//        foreach ($data['works'] as $nomenclature) {
//            if( $nomenclature == null) {
//                return redirect()
//                    ->route('pattern.edit', $id)
//                    ->with(['error' => "Ошибка сохранения. Не выбрана работа"]);
//            }
//        }
//
//        if(Func::array_has_dupes($data['nomenclatures']) || Func::array_has_dupes($data['works']) || Func::array_has_dupes($data['material'])) {
//            return redirect()
//                ->route('pattern.edit', $id)
//                ->with(['error' => "Ошибка сохранения. Присутствуют дубликаты"]);
//        }
//
//        $itemPattern = Pattern::find($id);
//        $result = $itemPattern
//            ->fill($data)
//            ->save();
//
//        PatternNomenclatures::where('pattern_id', $id)->delete();
//        foreach ($data['nomenclatures'] as $nomenclature) {
//            PatternNomenclatures::insert(['pattern_id' => $itemPattern->pattern_id, 'n_id' => $nomenclature]);
//        }
//
//        PatternWorks::where('pattern_id', $id)->delete();
//        foreach ($data['works'] as $key => $work) {
//            PatternWorks::insert(['pattern_id' => $itemPattern->pattern_id, 'work_id' => $work, 'count' => $data['workCount'][$key]]);
//        }
//
//        PatternAdditionalMaterials::where('pattern_id', $id)->delete();
//        foreach ($data['material'] as $key => $material) {
//            PatternAdditionalMaterials::insert(['pattern_id' => $itemPattern->pattern_id, 'material_id' => $material, 'count' => $data['materialCount'][$key]]);
//        }
//
//        if ($result) {
//            return redirect()
//                ->route('pattern.edit', $itemPattern->pattern_id)
//                ->with(['success' => "Успешно сохранено"]);
//        } else {
//            return back()
//                ->withErrors(['msg' => "Ошибка сохранения"])
//                ->withInput();
//        }
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
//        if(!isset($data['pattern'])) {
//            return back()
//                ->withErrors(['msg' => "Шаблон не выбран"])
//                ->withInput();
//        }
//
//        foreach ($data['pattern'] as $patternId) {
//
//            $patternCopy = Pattern::find($patternId);
//
//            $pattern = new Pattern();
//            $pattern->title = $patternCopy->title . ' copy';
//            $pattern->save();
//
//            $patternNomenclatures = PatternNomenclatures::where('pattern_id', $patternId)->get();
//            foreach ( $patternNomenclatures as $patternNomenclature) {
//                PatternNomenclatures::insert(['pattern_id' => $pattern->pattern_id, 'n_id' => $patternNomenclature->n_id]);
//            }
//
//            $patternWorks = PatternWorks::where('pattern_id', $patternId)->get();
//            foreach ( $patternWorks as $patternWork) {
//                PatternWorks::insert(['pattern_id' => $pattern->pattern_id, 'work_id' => $patternWork->work_id, 'count' => $patternWork->count]);
//            }
//
//            $patternMaterials = PatternAdditionalMaterials::where('pattern_id', $patternId)->get();
//            foreach ( $patternMaterials as $patternMaterial) {
//                PatternAdditionalMaterials::insert(['pattern_id' => $pattern->pattern_id, 'material_id' => $patternMaterial->material_id, 'count' => $patternMaterial->count]);
//            }
//        }
//
//        return redirect()
//            ->route('pattern.index')
//            ->with(['success' => "Шаблоны успешно скопированы"]);
//
//    }

}
