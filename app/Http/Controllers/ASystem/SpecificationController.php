<?php

namespace App\Http\Controllers\ASystem;

use App\Helpers\ExcelParser\ExcelParser;
use App\Http\Controllers\ASystem\BaseController;
use App\Http\Requests\UploadImportModelRequest;
use App\Models\Nomenclature;
use App\Models\Objct;
use App\Models\Specification;
use App\Models\SpecUnit;
use Illuminate\Http\Request;

class SpecificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paginator = Specification::paginate(4);
        return view('asystem.specifications.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $objects = Objct::all();
        return view('asystem.specifications.create', compact('objects'));
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

        $item = new Specification($data);
        $item->save();

        if($item) {
            return redirect()
                ->route('specification.edit', $item->spec_id)
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
        $item = Specification::find($id);
        $specUnits = SpecUnit::where('spec_id', $id)->active()->get();

        $objects =  Objct::all();
        return view('asystem.specifications.edit', compact('item', 'objects', 'specUnits'));
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

        $item = Specification::find($id);

        $data = $request->all();
        $result = $item
            ->fill($data)
            ->save();

        if ($result) {
            return redirect()
                ->route('specification.edit', $item->spec_id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    public function upload($id)
    {
        return view('asystem.specifications.upload', ['id' => $id]);
    }

    public function uploadSave(UploadImportModelRequest $request)
    {
        $id =  $request->input('id');
        $originalFile = $request->file('import_file');
        $ext = $originalFile->getClientOriginalExtension();

        $sheetData = [];
        if ($ext == 'xlsx') {
            $sheetData = ExcelParser::get_array_xlsx($request->file('import_file'));
        }  elseif ($ext == 'xls') {
            $sheetData = ExcelParser::get_array_xls($request->file('import_file'));
        }

        $nomenclatures = Nomenclature::all()->toArray();

        //ищем максимальную версию и инкриминируем
        $maxVer = SpecUnit::where('spec_id', $id)->max('ver') + 1;
        //деактивация всех номенклатур
        SpecUnit::where('spec_id', $id)->update(['is_active' => 0]);

        $resultNotSearch = [];

        foreach ($sheetData as $data) {

            $title = $data[2];
            $count = $data[4];

            $chk = false;
            foreach ($nomenclatures as $nomenclature) {
                if ($nomenclature["title"] == $title) {
                    SpecUnit::updateOrInsert(['spec_id' => $id, 'n_id' => $nomenclature["n_id"], 'count' => $count, 'ver' => $maxVer, 'is_active' => 1]);
                    $chk = true;
                }
            }
            if(!$chk && !is_null($title)) {
                $resultNotSearch[] = $title;
            }
        }

        if (empty($resultNotSearch)) {
            return redirect()
                ->route('specification.edit', $id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            $resultSeparated = implode(", ", $resultNotSearch);
            return redirect()
                ->route('specification.edit', $id)
                ->withErrors(['msg' => "Ошибка сохранения. Не добавлена наменклатура: {$resultSeparated}"])
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
