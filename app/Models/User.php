<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable {
        notify as protected laravelNotify;
    }

    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    // *override trait notify function
    public function notify($instance)
    {
        if($this->id == Auth::id())
        {
           return;
        }

        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'introduction', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model)
    {
       return $this->id == $model->user_id;
    }

    public function replies()
    {
       return $this->hasMany(Reply::class);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setPasswordAttribute($value)
    {
        if(strlen($value) != 60)
        { // not encrypt so need encrypt
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        if( !starts_with($path, 'http') )
        {
            // split joint url
            $path = config('app.url'). "/uploads/images/avatars/$path";
        }
        $this->attributes['avatar'] = $path;
    }
}
