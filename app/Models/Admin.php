<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Admin extends Model {
	public function user(): MorphOne {
		return $this->morphOne(User::class, 'user');
	}
}