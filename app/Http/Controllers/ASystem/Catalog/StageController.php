<?php

namespace App\Http\Controllers\ASystem\Catalog;

use App\Http\Requests\UploadImportModelRequest;
use App\Models\Material;
use App\Models\Materials2object;
use App\Models\Objct;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends CatalogController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $paginator = Stage::paginate(4);
        return view('asystem.stages.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $item = new Stage();
        $objects = Objct::all();

        return view('asystem.stages.create', compact('item', 'objects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();

        $item = new Stage([
            'title' => $data['title'], /// вот тут текущий пользователь
            'object_id' => $data['object_id'],
        ]);

        $result = $item->save();

        if ($result) {
            return redirect()
                ->route('stage.edit', $item->stage_id)
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $item = Stage::where('stage_id', '=', $id)->first();
        $objects = Objct::all();
        $materials = \DB::table('m2o_view')->where('s_id', $id)->get();

        return view('asystem.stages.edit', compact('item', 'objects', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Stage::find($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();
        $item->title = $data['title'];
        $item->object_id = $data['object_id'];

        $result = $item->save();

        if ($result) {
            return redirect()
                ->route('stage.edit', $item->stage_id)
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function upload($id)
    {
        $item = Stage::findOrFail($id);

        return view('asystem.stages.upload', compact('item'));
    }

/*    public function uploadSave(UploadImportModelRequest $request, $id)
    {
        $originalFile = $request->file('import_file');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setDelimiter("\t");
        $spreadsheet = $reader->load($originalFile->getRealPath());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        //ищем максимальную версию для этого этапа и инкриминируем
        $maxVer = Materials2object::where('stage_id', $id)->max('ver') + 1;
        //деактивация всех материалов для этого этапа
        Materials2object::where('stage_id', $id)->update(['is_active' => 0]);

        foreach ($sheetData AS $data) {

            //------------------------------------------------------------------
            // ищем материал с title, если нет, то добавляем
            $itemMaterial = Material::where('title', $data[1])->first();
            if (empty($itemMaterial)) {
                $itemMaterial = new Material([
                    'title' => $data[1],
                    'notes' => $data[2],
                ]);
                $itemMaterial->save();
            }
            //------------------------------------------------------------------
            // ищем связку с material_id и stage_id
            // если нет связки, то добавляем с версией - $maxVer. активирует
            $itemM2O = Materials2object::where('material_id', $itemMaterial->material_id)->where('stage_id', $id)->first();
            if (empty($itemM2O)) {
                $itemM2O = new Materials2object([
                    'material_id' => $itemMaterial->material_id,
                    'stage_id' => $id,
                    'ver' => $maxVer,
                    'price' => floatval($data[3]),
                    'count' => $data[4],
                    'units' => $data[5],
                    'purchase_price' => floatval($data[6]),
                    'work_price' => floatval($data[7]),
                    'is_active' => 1,
                ]);
                $itemM2O->save();
            }
            // если есть связка, то обновляем с новыми данными. версию не меняем. активируем
            if (!empty($itemM2O)) {
                $itemM2O->price = floatval($data[3]);
                $itemM2O->count = $data[4];
                $itemM2O->units = $data[5];
                $itemM2O->purchase_price = floatval($data[6]);
                $itemM2O->work_price = floatval($data[7]);
                $itemM2O->is_active = 1;
                $itemM2O->save();
            }
            //------------------------------------------------------------------
        }

        return redirect()
            ->route('stage.edit', $id)
            ->with(['success' => "Файл успешно загружен"]);
    }*/


    public function uploadSave(UploadImportModelRequest $request, $id) {

        $originalFile = $request->file('import_file');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

        $spreadsheet = $reader->load($originalFile->getRealPath());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        dd($sheetData);

    }
}
