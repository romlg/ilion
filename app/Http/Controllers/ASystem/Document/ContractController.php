<?php

namespace App\Http\Controllers\ASystem\Document;

use App\Http\Controllers\ASystem\Document\DocumentController;
use App\Http\Requests\ContractUpdateRequest;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ASystem\BaseController;

class ContractController extends DocumentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = Contract::paginate(4);
        return view('asystem.contracts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Contract();

        return view('asystem.contracts.edit', compact('item'));
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

        $data['is_signed'] = (isset($data['is_signed']) && $data['is_signed'] == 'on') ? 1 : 0;
        $data['contract_date'] = strtotime($data['contract_date']);
        $item = new Contract($data);
        $item->save();

        if($item) {
            return redirect()
                ->route('contract.edit', $item->contract_id)
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
        $item = Contract::findOrFail($id);

        return view('asystem.contracts.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContractUpdateRequest $request, $id)
    {
        $item = Contract::find($id);
        if(empty($item)) {
            return back()
                ->withErrors(['msg' => "Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();
        $data['is_signed'] = (isset($data['is_signed']) && $data['is_signed'] == 'on') ? 1 : 0;
        $data['contract_date'] = strtotime($data['contract_date']);
        $result = $item
            ->fill($data)
            ->save();

        if ($result) {
            return redirect()
                ->route('contract.edit', $item->contract_id)
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
