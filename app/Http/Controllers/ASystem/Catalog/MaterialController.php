<?php

namespace App\Http\Controllers\ASystem\Catalog;

use App\Http\Controllers\ASystem\PatternMaterialsController;
use App\Models\Material;
use App\Models\Price;
use App\Models\Producer;
use App\Models\PatternMaterials;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MaterialController extends CatalogController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = Material::paginate(10);
        return view('asystem.materials.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = config('units');
        $producers = Producer::all();
        $patternMaterials = PatternMaterials::all();

        return view('asystem.materials.create', compact('units', 'producers', 'patternMaterials'));
        //return view('asystem.materials.edit', compact('item', 'units'));
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
            'vendor_code' => 'required|min:2|max:255',
            'unit' => 'required',
            'producer_id' => 'required',
            'pattern_material_id' => 'required'
        ]);

        $data = $request->input();

        $itemMaterial = Material::create([
            'title'       => $data['title'],
            'vendor_code' => $data['vendor_code'],
            'unit'        => $data['unit'],
            'producer_id' => $data['producer_id'],
            'pattern_material_id' => $data['pattern_material_id']
        ]);

        $result = $itemMaterial->save();

        if(!is_null($data['sprice']) || !is_null($data['oprice']) || !is_null($data['price'])) {
            Price::create([
                'material_id' => $itemMaterial->material_id,
                'sprice'  => $data['sprice'],
                'oprice'  => $data['oprice'],
                'price'   => $data['price'],
                'user_id' => Auth::id()
            ]);
        }

        if($result) {
            return redirect()
                ->route('material.edit', $itemMaterial->material_id)
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
        $item = Material::findOrFail($id);
        $units = config('units');
        $producers = Producer::all();
        $patternMaterials = PatternMaterials::all();
        $price = Price::where('material_id', $id)->latest('price_id')->first();

        return view('asystem.materials.edit', compact('item', 'units', 'producers', 'patternMaterials', 'price'));
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
            'title' => 'required|min:2|max:255',
            'vendor_code' => 'required|min:2|max:255',
            'unit' => 'required',
            'producer_id' => 'required',
            'pattern_material_id' => 'required'
        ]);

        $data = $request->all();

        $itemMaterial = Material::where('material_id', $id)
            ->update([
                'title'       => $data['title'],
                'vendor_code' => $data['vendor_code'],
                'unit'        => $data['unit'],
                'producer_id' => $data['producer_id'],
                'pattern_material_id' => $data['pattern_material_id']
            ]);

        $record = Price::where('material_id', $id)->latest('price_id')->first();
        if($record) {
            if($record->sprice != $data['sprice'] || $record->oprice != $data['oprice'] || $record->price != $data['price']) {
                Price::create([
                    'material_id' => $id,
                    'sprice'  => $data['sprice'],
                    'oprice'  => $data['oprice'],
                    'price'   => $data['price'],
                    'user_id' => Auth::id()
                ]);
            }
        } elseif (!is_null($data['sprice']) || !is_null($data['oprice']) || !is_null($data['price'])) {
            Price::create([
                'material_id' => $id,
                'sprice'  => $data['sprice'],
                'oprice'  => $data['oprice'],
                'price'   => $data['price'],
                'user_id' => Auth::id()
            ]);
        }

        if ($itemMaterial) {
            return redirect()
                ->route('material.edit', $id)
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

        if (!isset($data['material'])) {
            return back()
                ->withErrors(['msg' => "Шаблоны не выбраны"])
                ->withInput();
        }

        foreach ($data['material'] as $materialId) {

            $materialCopy = Material::find($materialId);
            $material = new Material($materialCopy->getOriginal());
            $material->save();
        }

        return redirect()
            ->route('material.index')
            ->with(['success' => "Шаблоны успешно скопированы"]);
    }

}
