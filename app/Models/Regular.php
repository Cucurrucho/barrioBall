<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regular extends Model
{
    public function user()
    {
        return $this->morphOne(User::class, 'user');
    }
    //
}
