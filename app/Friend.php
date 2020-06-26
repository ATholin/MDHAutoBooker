<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = [
        'name',
        'mdh_username',
        'color'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
