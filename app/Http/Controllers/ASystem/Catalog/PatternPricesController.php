<?php

namespace App\Http\Controllers\ASystem\Catalog;

use App\Helpers\Func\Func;
use App\Http\Controllers\ASystem\BaseController;
use App\Models\Material;
use App\Models\Nomenclature;
use App\Models\PatternAdditionalMaterials;
use App\Models\PatternExpendableMaterials;
use App\Models\PatternMaterials;
use App\Models\PatternNomenclatures;
use App\Models\PatternWorks;
use App\Models\PatternPrices;
use App\Models\Work;
use Illuminate\Http\Request;


class PatternPricesController extends CatalogController
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
        $patternMaterials = PatternMaterials::all();
        $materials = Material::all();

        return view('asystem.pattern_prices.create',
            compact('nomenclatures', 'works', 'patternMaterials', 'materials')
        );
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

        if(Func::array_has_dupes($data['works']) || Func::array_has_dupes($data['pmaterial']) || Func::array_has_dupes($data['material'])) {
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

        foreach ($data['pmaterial'] as $key => $pmaterial) {
            PatternAdditionalMaterials::insert(['pattern_id' => $itemPattern->pattern_price_id, 'material_id' => $pmaterial]);
        }

        foreach ($data['material'] as $key => $material) {
            PatternExpendableMaterials::insert(['pattern_id' => $itemPattern->pattern_price_id, 'material_id' => $material, 'count' => $data['materialCount'][$key]]);
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

        $nomenclatures = Nomenclature::active()->get();
        $works = Work::active()->get();
        $patternMaterials = PatternMaterials::all();
        $materials = Material::all();

        //dd($item->expendableMaterials);

        //dd($item->expendableMaterials->where('pem_id', 3)->first()->count);

        return view('asystem.pattern_prices.edit',
            compact('item', 'nomenclatures', 'works', 'patternMaterials', 'materials')
        );
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
        $validatedData = $request->validate([
            'title' => 'required|min:2|max:255'
        ]);

        $data = $request->all();

        if( $data['nomenclatures'] == null) {
            return redirect()
                ->route('patternPrices.create')
                ->with(['error' => "Ошибка сохранения. Не выбрана номенклатура"]);
        }

        foreach ($data['works'] as $work) {
            if( $work == null) {
                return redirect()
                    ->route('patternPrices.edit', $id)
                    ->with(['error' => "Ошибка сохранения. Не выбрана работа"]);
            }
        }

        if(Func::array_has_dupes($data['works']) || Func::array_has_dupes($data['pmaterial'])) {
            return redirect()
                ->route('patternPrices.edit', $id)
                ->with(['error' => "Ошибка сохранения. Присутствуют дубликаты"]);
        }

        $itemPattern = PatternPrices::find($id);
        $result = $itemPattern
            ->fill($data)
            ->save();

        PatternNomenclatures::where('pattern_id', $id)->delete();
        PatternNomenclatures::insert(['pattern_id' => $itemPattern->pattern_price_id, 'n_id' => $data['nomenclatures']]);

        PatternWorks::where('pattern_id', $id)->delete();
        foreach ($data['works'] as $key => $work) {
            PatternWorks::insert(['pattern_id' => $itemPattern->pattern_price_id, 'work_id' => $work, 'count' => $data['workCount'][$key]]);
        }

        PatternAdditionalMaterials::where('pattern_id', $id)->delete();
        foreach ($data['pmaterial'] as $key => $pmaterial) {
            PatternAdditionalMaterials::insert(['pattern_id' => $itemPattern->pattern_price_id, 'material_id' => $pmaterial]);
        }

        if ($result) {
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

        if(!isset($data['pattern'])) {
            return back()
                ->withErrors(['msg' => "Шаблоны не выбраны"])
                ->withInput();
        }

        foreach ($data['pattern'] as $patternId) {

            $patternPriceCopy = PatternPrices::find($patternId);

            $patternPrice = new PatternPrices();
            $patternPrice->title = $patternPriceCopy->title;
            $patternPrice->save();

            $patternNomenclature = PatternNomenclatures::where('pattern_id', $patternId)->first();
            PatternNomenclatures::insert(['pattern_id' => $patternPrice->pattern_price_id, 'n_id' => $patternNomenclature->n_id]);

            $patternWorks = PatternWorks::where('pattern_id', $patternId)->get();
            foreach ( $patternWorks as $patternWork) {
                PatternWorks::insert(['pattern_id' => $patternPrice->pattern_price_id, 'work_id' => $patternWork->work_id, 'count' => $patternWork->count]);
            }

            $patternMaterials = PatternAdditionalMaterials::where('pattern_id', $patternId)->get();
            foreach ( $patternMaterials as $patternMaterial) {
                PatternAdditionalMaterials::insert(['pattern_id' => $patternPrice->pattern_price_id, 'material_id' => $patternMaterial->material_id]);
            }
        }

        return redirect()
            ->route('patternPrices.index')
            ->with(['success' => "Шаблоны успешно скопированы"]);

    }

}
