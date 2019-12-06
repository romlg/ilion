<?php

namespace App\Http\Controllers\ASystem\Report;

use Illuminate\Http\Request;
use App\User;

class UsersSheetController extends ReportController
{

    public function index()
    {
        $users = User::all();
        return view('asystem.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('asystem.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();

        //dd($data);
        $item = User::find($id);

        $item->name = $data['name'];
        $item->middle_name = $data['middle_name'];
        $item->last_name = $data['last_name'];
        $item->email = $data['email'];
        $item->is_active = $data['active'];
        $item->admin = $data['role'];
        $item->phone = $data['phone'];
        $item->bod = $data['bod'];
        $item->post = $data['post'];

        $result = $item->save();

        if ($result) {
            return redirect()
                ->route('users.edit', $item->id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
        //dd($id);
        //dd($request->all());
    }
}
