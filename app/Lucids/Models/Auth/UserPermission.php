<?php

namespace Lucids\Models\Auth;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserPermission extends Eloquent {

	protected $table = "users_permissions",
			  $fillable = ['is_admin', 'is_staff', 'is_ban'];

	//disable timestamp in this eloquent model		  
	public $timestamps = false;

	public static $defaults = [
		'is_admin' => false,
		'is_staff' => false,
		'is_ban' => false,
	];
}