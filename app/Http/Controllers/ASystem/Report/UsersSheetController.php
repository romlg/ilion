<?php

namespace App\Http\Controllers\ASystem\Report;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersSheetController extends ReportController
{

    public function index()
    {
        $users = User::all();
        return view('asystem.users.index', compact('users'));
    }

    public function create()
    {
        $objects = \App\Models\Objct::all();
        return view('asystem.users.create', compact('objects'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|min:2|max:255',
            'middle_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|unique:users|max:255',
            'phone' => 'required|min:2|max:255',
            'post' => 'required|min:2|max:255',
            'password' => 'required|min:8',
            'password_check' => 'required|min:8',
        ]);

        $data = $request->input();

        if($data["password"] != $data["password_check"]) {
            return back()->withErrors(['msg' => "Ошибка. Пароли не совпали"])->withInput();
        }

        $item = new User();

        $item->name = $data['name'];
        $item->middle_name = $data['middle_name'];
        $item->last_name = $data['last_name'];
        $item->email = $data['email'];
        $item->is_active = $data['active'];
        $item->is_admin = $data['role'];

        $item->password = Hash::make($data['password']);

        $item->phone = $data['phone'];
        $item->bod = $data['bod'];
        $item->post = $data['post'];
        $item->object_id = $data['objects'];

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
        //
    }

    public function edit($id)
    {
        $objects = \App\Models\Objct::all();

        $user = User::where('id', $id)->first();
        return view('asystem.users.edit', compact('user', 'objects'));
    }

    public function update(Request $request, $id)
    {


        $validatedData = $request->validate([
            'name' => 'required|min:2|max:255',
            'middle_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            //'email' => 'required|unique:users|max:255',
            'phone' => 'required|min:2|max:255',
            'post' => 'required|min:2|max:255',
//            'password' => 'required|min:8',
//            'password_check' => 'required|min:8',
        ]);

        $data = $request->all();

        //dd($data);

        $item = User::find($id);

        $item->name = $data['name'];
        $item->middle_name = $data['middle_name'];
        $item->last_name = $data['last_name'];
        //$item->email = $data['email'];
        $item->is_active = $data['active'];
        $item->is_admin = $data['role'];
        $item->phone = $data['phone'];
        $item->bod = $data['bod'];
        $item->post = $data['post'];
        $item->object_id = $data['objects'];

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
    }
}
