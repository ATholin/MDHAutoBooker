<?php

namespace App;

use App\Events\BookingSuccessful;
use App\Facades\Kronox;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ScheduledBooking extends Model
{
    protected $fillable = ['date', 'interval', 'mdh_username', 'room', 'message', 'recurring', 'result', 'kronox_credentials_id'];

    protected $dates = ['created_at', 'updated_at', 'date'];

    protected $casts = [
        'recurring' => 'boolean'
    ];

    public function book()
    {
        $result = Kronox::book($this);

        BookingSuccessful::dispatchIf($result === 'OK', $this);

        $this->update([
            'result' => $result
        ]);
    }

    public function getBookingsCountAttribute()
    {
        return count(Kronox::getBookings($this->getSession()));
    }

    public function getSession()
    {
        if ($this->updated_at->lt(now()->subMinutes(15))) {
            $this->poll();
        }

        return $this->JSESSIONID;
    }

    public function poll()
    {

        $result = Kronox::poll($this->JSESSIONID);
        if ($result != 'OK') {
            $username = $this->username;
            $password = Crypt::decrypt($this->password);
            $this->JSESSIONID = $this->kronoxService->login($username, $password);
        }

        $this->save();
    }

    public function credentials()
    {
        return $this->belongsTo(KronoxCredentials::class, 'kronox_credentials_id');
    }
}
