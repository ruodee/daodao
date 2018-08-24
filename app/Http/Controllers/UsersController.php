<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

class UsersController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth',['except' => ['show','create','store']]);
        $this->middleware('guest',['only' => ['create']]);
    }
    //
    public function create()
    {

        return view('users.create');
    }

    public function show(User $user)
    {
       // dd($user->gravatar());
        return view('users.show',compact('user'));

    }

    public function store(Request $request)
    {
        $this->validate($request,[

            'name' => 'required|max:50|min:3',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'

        ]);

        $user = User::create([
            'name'=> $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        session()->flash('success','欢迎，注册成功了，您将在这里扬帆起航！');
        return redirect()->route('users.show',[$user]);
    }

    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    public function update(User $user,Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:50|unique:users',
            'password' => 'nullable|confirm|min:6'
        ]);
        $this->authorize('update',$user);
        $data = [];
        $data['name'] = $request->name;
        if($request->password)
            $data['password'] = bcrypt($request->password);
        $user->update($data);

        session()->flash('success','个人资料更新成功！');
        return redirect()->route('users.show',$user->id);

    }
}
