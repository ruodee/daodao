<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

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
}
