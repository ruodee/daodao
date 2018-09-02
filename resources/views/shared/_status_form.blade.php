<form action="{{ route('statuses.store') }}" method="POST">
    @include('shared._error')
    {{ csrf_field() }}
    <textarea name="content" class="form-control" rows="3" placeholder="聊聊天，买卖车" name="content" >{{ old('content') }}</textarea>
    <button type="submit" class="btn btn-primary pull-right">发布</button>
</form>