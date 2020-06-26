<?php

namespace App;

use App\Facades\Kronox;
use Illuminate\Database\Eloquent\Model;

class KronoxCredentials extends Model
{

    protected $fillable = [
        'JSESSIONID',
        'username',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function getSessionAttribute()
    {
        if (!$this->JSESSIONID || $this->updated_at->lt(now()->subMinutes(15))) {
            return $this->poll();
        }

        return $this->JSESSIONID;
    }

    public function poll()
    {

        if ($this->JSESSIONID && Kronox::poll($this->JSESSIONID)) {

            return $this->JSESSIONID;
        }

        $session = Kronox::login($this->username, $this->password);
        $this->update([
            'JSESSIONID' => $session
        ]);

        return $session;
    }

    public function getBookingsCountAttribute()
    {
        return $this->bookings()->count();
    }

    public function bookings()
    {
        return $this->hasMany(ScheduledBooking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
