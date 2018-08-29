<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Mail;
use Auth;

class UsersController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth',['except' => ['show','create','store','index','confirmEmail']]);
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
        $this->sendEmailConfirmationTo($user);
        session()->flash('success','验证邮件已发送到你注册的邮箱，请查收邮件进行验证！');
        return redirect('/');
    }

    //发送验证信息邮件
    protected function sendEmailConfirmationTo(User $user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        //$from = 'aufree@yousails.com';
        //$name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册daodao,请点击验证链接进行邮箱验证！";

        Mail::send($view,$data,function($message) use ($to,$subject){
            $message->to($to)->subject($subject);
        });
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
    //list all users
    public function index()
    {
        $users = User::paginate(10);
        //dd($users);
        return view('users.index',compact('users'));
    }
    //删除用户

    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户');
        return back();
    }
    //验证邮箱
    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','激活成功，请开始在此扬帆起航');
        return redirect()->route('users.show',[$user]);
    }
}
