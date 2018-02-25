<?php

namespace Lucids\Models\Auth;

use Illuminate\Database\Eloquent\Model as Eloquent;

class PasswordReset extends Eloquent {

	protected $table = "password_resets", 
			  $fillable = ['token'];

	public function setUpdatedAt($value){

	}

	public function user() {
		return $this->belongsTo('Lucids\Models\Auth\User');
	}
}