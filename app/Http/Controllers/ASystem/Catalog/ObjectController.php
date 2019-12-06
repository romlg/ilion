<?php

namespace App\Http\Controllers\ASystem\Catalog;

use App\Http\Requests\UploadImportModelRequest;
use App\Models\Material;
use App\Models\Materials2object;
use App\Models\Objct as Obj;
use Illuminate\Http\Request;
use App\Library\Utility;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class ObjectController extends CatalogController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $paginator =  Obj::paginate(4);
        return view('asystem.objects.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Obj();

        return view('asystem.objects.edit', compact('item'));
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

        $item = new Obj($data);
        $item->save();

        if($item) {
            return redirect()
                ->route('object.edit', $item->object_id)
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
        $item = Obj::findOrFail($id);

        //$materials = $item->materials()->get();

//        foreach ($materials as $material) {
//            var_dump($material);
//        }

        $materials = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            ->where('materials2objects.object_id', $item->object_id)
            ->select('materials.title', 'materials.material_id', 'materials2objects.units', 'materials2objects.count')
            ->get();

        $materialsAll = \DB::table('materials')
            ->leftJoin('materials2objects', 'materials.material_id', '=', 'materials2objects.material_id')
            //->where('materials2objects.object_id', $item->object_id)
            ->select('materials.title', 'materials.material_id', 'materials2objects.units', 'materials2objects.count')
            ->get();

        //dd($materials);
        return view('asystem.objects.edit', compact('item', 'materials', 'materialsAll'));
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

        // само название материала сохранить в таблицу материалов,
        // а его ИД и количество в таблицу materials2objects

        $item = Obj::find($id);
        if(empty($item)) {
            return back()
                ->withErrors(['msg' => "Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();

        foreach ($data['material'] As $key => $val) {

            $chkMaterial = Materials2object::where('material_id', '=', $val)->where('object_id', '=', $id)->first();

            if ($data['count'][$key] && $chkMaterial === null) {
                $materials2object = new Materials2object([
                    'material_id' => $val,
                    'object_id' => $id,
                    'purchase_price' => 0,
                    'sale_price' => 0,
                    'count' => $data['count'][$key],
                    'units' => $data['units'][$key]
                ]);
                $materials2object->save();
            }
        }

        $result = $item
            ->fill($data)
            ->save();

        if ($result) {
            return redirect()
                ->route('object.edit', $item->object_id)
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

    public function upload($id)
    {
        $item = Obj::findOrFail($id);

        return view('asystem.objects.upload', compact('item'));
    }

    public function uploadSave(UploadImportModelRequest $request, $id)
    {
        $originalFile = $request->file('import_file');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $spreadsheet = $reader->load($originalFile->getRealPath());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//        print_r($sheetData);

        \DB::beginTransaction();
        $status = true;
        foreach($sheetData AS $data) {
            $item = Material::where('title', $data[0])->first();

            $materialId = "";
            if($item) {
                $materialId = $item->material_id;
            } else {
                $newMaterial = new Material([
                    'title' => $data[0],
                    'notes' => $data[1]
                ]);
                $newMaterial->save();
                $materialId = $newMaterial->material_id;
            }

            $itemMaterial2Object = Materials2object::where('material_id', $materialId)
                ->where('object_id', $id)
                ->select('id')
                ->value('id');

            if ($itemMaterial2Object) {
                $itemMaterial2ObjectNew = $item
                    ->fill([
                        'id' => $itemMaterial2Object,
                        'material_id' => $materialId,
                        'object_id' => $id,
                        'purchase_price' => $data[2],
                        'sale_price' => $data[4],
                        'count' => $data[5],
                        'units' => $data[6]
                    ])
                    ->save();
            } else {
                $itemMaterial2ObjectNew = new Materials2object([
                    'material_id' => $materialId,
                    'object_id' => $id,
                    'purchase_price' => Utility::changeDecimalSeparator($data[2]),
                    'sale_price' => Utility::changeDecimalSeparator($data[4]),
                    'count' => $data[5],
                    'units' => $data[6]
                ]);
                $itemMaterial2ObjectNew->save();
            }

            $status = $itemMaterial2ObjectNew ? true : false;
        }

        if ($status) {
            \DB::commit();
            return redirect()
                ->route('object.edit', $id)
                ->with(['success' => "Файл успешно загружен"]);
        } else {
            \DB::rollBack();
            return redirect()
                ->route('object.edit', $item->object_id)
                ->withErrors(['msg' => "Загрузка файла не удалась"]);
        }

    }

}
