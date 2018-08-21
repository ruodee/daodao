@extends('layouts.default')
@section('title','登录')
@section('content')

    <div class="col-md-offset-2 col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>登录</h5>
            </div>
            <div class="panel-body">
                @include('shared._error')
                <form action="{{ route('login') }}" method="POST">
                    {{  csrf_field() }}
                    <div class="form-group">
                        <label for="email">邮箱：</label>
                        <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                       <label for="password">密码：</label>
                       <input type="text" class="form-control" name="password" value="{{ old('password') }}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">提交</button>
                    </div>
                </form>
                <hr>
                <p>还没有账号？<a href="{{ route('users.create') }}">现在就去创建？</a></p>
            </div>
        </div>
    </div>

@stop