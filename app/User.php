<?php

namespace App;

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

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/$hash";
    }

    public function credentials()
    {
        return $this->hasMany(KronoxCredentials::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(ScheduledBooking::class, KronoxCredentials::class, 'user_id', 'kronox_credentials_id');
    }

    public function friends()
    {
        return $this->hasMany(Friend::class);
    }
}
