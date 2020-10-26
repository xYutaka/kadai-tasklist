<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * このユーザが所有する投稿。（Taskモデルとの関係を定義）
     * Userモデルファイルにも一対多の表現を記述しておきます。 
     * Userが持つTaskは複数存在するため、 function tasks() のように複数形tasksでメソッドを定義します。
     * 中身は return $this->hasMany(Task::class); とします（先ほどとは異なりhasManyを使用していることに着目してください）。
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    /**
     * このユーザに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount('tasks');
    }
}


    