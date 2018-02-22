<?php

namespace App\Models;

use App\Models\Traits\ActiveUserHelper;
use App\Models\Traits\LastActivedAtHelper;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use LastActivedAtHelper;
    use ActiveUserHelper;

    use Notifiable {

        notify as protected laravelNotify;
    }

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'introduction', 'phone', 'weixin_openid', 'weixin_unionid'
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
     * 话题关联
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    /**
     * 回复
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 通知
     */
    public function notify($instance)
    {
        if ($this->id == Auth::id()) {

            return;
        }

        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    /**
     * 将所有通知状态标记为已读
     */
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}
