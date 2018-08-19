@extends('layouts.default')
@section('content')
<div class="jumbotron">
    <h1>Hello DaoDao</h1>
    <p class="lead">
        你现在所看到的是道道，加入我们一起学习！
    </p>
    <p>
        一切，将从这里开始。
    </p>
    <p>
        <a href="{{ route('users.create') }}" class="btn btn-lg btn-success" role="button">立即注册</a>
    </p>
</div>
@stop