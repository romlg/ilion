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

        $paginator =  Stage::paginate(4);
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
     * @param  \Illuminate\Http\Request  $request
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

        if($result) {
            return redirect()
                ->route('stage.edit', $item->object_id)
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
        $item = Stage::where('stage_id', '=', $id)->first();
        $objects = Objct::all();
        $materials = \DB::table('m2o_view')->where('s_id', $id)->get();

        return view('asystem.stages.edit', compact('item', 'objects', 'materials'));
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
        $item = Stage::find($id);

        if(empty($item)) {
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
     * @param  int  $id
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

    public function uploadSave(UploadImportModelRequest $request, $id)
    {
        $originalFile = $request->file('import_file');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $spreadsheet = $reader->load($originalFile->getRealPath());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        foreach($sheetData AS $data) {


            $itemMaterial = new Material([
                'title' => $data[1],
                'notes' => $data[2],
            ]);
            $itemMaterial->save();

            $itemM2O = new Materials2object([
                'material_id' => $itemMaterial->material_id,
                'stage_id' => $id,
                'ver' => 1,
                'price' => floatval($data[3]),
                'count' => $data[4],
                'units' => $data[5],
                'purchase_price' => floatval($data[6]),
                'work_price' => floatval($data[7]),
            ]);
            $itemM2O->save();
            //var_dump($data);
        }

        return redirect()
            ->route('stage.edit', $id)
            ->with(['success' => "Файл успешно загружен"]);

    }
}
