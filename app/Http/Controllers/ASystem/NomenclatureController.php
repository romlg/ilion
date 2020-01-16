<?php

namespace App\Http\Controllers\ASystem;

use App\Helpers\ExcelParser\ExcelParser;
use App\Http\Controllers\ASystem\BaseController;
use App\Http\Requests\UploadImportModelRequest;
use App\Models\Group;
use App\Models\Nomenclature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NomenclatureController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paginator =  Nomenclature::paginate(4);
        return view('asystem.nomenclatures.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $groups =  Group::all();
        return view('asystem.nomenclatures.create', compact('groups'));
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
        //dd($request->input());
        $data = $request->input();

        $item = new Nomenclature($data);
        $item->save();

        if($item) {
            return redirect()
                ->route('nomenclature.edit', $item->n_id)
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
        $item = Nomenclature::findOrFail($id);
        $groups =  Group::all();
        return view('asystem.nomenclatures.edit', compact('item', 'groups'));
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
        $item = Nomenclature::find($id);

        $data = $request->all();
        $result = $item
            ->fill($data)
            ->save();

        if ($result) {
            return redirect()
                ->route('nomenclature.edit', $item->n_id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    public function upload()
    {
        return view('asystem.nomenclatures.upload');
    }

    public function uploadSave(UploadImportModelRequest $request)
    {
        $originalFile = $request->file('import_file');
        $ext = $originalFile->getClientOriginalExtension();

        $sheetData = [];
        if ($ext == 'xlsx') {
            $sheetData = ExcelParser::get_array_xlsx($request->file('import_file'));
        }  elseif ($ext == 'xls') {
            $sheetData = ExcelParser::get_array_xls($request->file('import_file'));
        }

        $nomenclature = [];
        foreach ($sheetData as  $data) {
            if(!is_null($data[7])) {
                $nomenclature[] = $data[7];
                Nomenclature::updateOrCreate(['title' => $data[7], 'is_active' => 1]);
            }
        }
        dd($nomenclature);

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
