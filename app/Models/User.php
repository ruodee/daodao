<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    //一个用户有多个微博
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }
    //实现用户模型检测
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });

    }

    //用户头像
    public function gravatar($size = 100)
    {
        $hash = md5(strtolower(trim($this->email)));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

        //动态流，取出用户发布过的statuses和用户关注的人发布的statuses
    public function feed()
    {
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids,Auth::user()->id);
        return Status::whereIn('user_id',$user_ids)->with('user')->orderBy('created_at','desc');
    }
    //一个用户实例的粉丝，粉丝还是用户，我们称之为followers.
    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }
    //一个用户关注的用户集合
    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }
     //关注follow
    public function follow($user_ids)
    {
        if(!is_array($user_ids))
            $user_ids = compact('user_ids');

        $this->followings()->sync($user_ids,false);
    }
    //取消关注
    public function unfollow($user_ids)
    {
        if(!is_array($user_ids))
            $user_ids = compact('user_ids');
        $this->followings()->detach($user_ids);
    }
    //判断当前用户是否关注了查询用户
    public function isFollowings($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
