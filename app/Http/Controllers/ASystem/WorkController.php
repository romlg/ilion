<?php

namespace App\Http\Controllers\ASystem;

use App\Helpers\ExcelParser\ExcelParser;
use App\Http\Controllers\ASystem\BaseController;
use App\Http\Requests\UploadImportModelRequest;
use App\Models\Group;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paginator =  Work::paginate(4);
        return view('asystem.works.index', compact('paginator'));
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
        return view('asystem.works.create', compact('groups'));
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

        $item = new Work($data);
        $item->save();

        if($item) {
            return redirect()
                ->route('work.edit', $item->n_id)
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
        $item = Work::findOrFail($id);
        $groups =  Group::all();
        return view('asystem.works.edit', compact('item', 'groups'));
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
        $item = Work::find($id);

        $data = $request->all();
        $result = $item
            ->fill($data)
            ->save();

        if ($result) {
            return redirect()
                ->route('work.edit', $item->work_id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    public function upload()
    {
        return view('asystem.works.upload');
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

        //dd($sheetData);

        //$works = [];
        foreach ($sheetData as  $data) {
            if(!is_null($data[1]) && !is_null($data[2]) && !is_null($data[3]) && !is_null($data[4])) {
                Work::updateOrCreate(['title' => $data[1], 'units' => $data[2], 'wtime' => $data[3], 'wprice' => $data[4], 'is_active' => 1]);
            }
        }

        return redirect()
            ->route('work.index')
            ->with(['success' => "Файл успешно загружен"]);

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
