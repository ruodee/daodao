<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller
{
    //
    public function create()
    {
        return view('sessions.create');
    }

    //public function store

    public function store(Request $request)

    {
        $credentials = $this->validate($request,[

            'email' => "required|email|max:255",
            'password' => "required"

        ]);

        if(Auth::attempt($credentials))
            {
                session()->flash('success','登陆成功，欢迎回来！');
                return redirect()->route('users.show',[Auth::id]);
            }
            else
            {
                session()->flash('danger','很抱歉，你的邮箱和密码不匹配');
                return redirect()->back()->withInput($request->except('password'));
            }
    }
}