<?php

namespace Lucids\Models\Deals;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Lucids\Models\Deals\Deal;

class UserDeal extends Eloquent {

	protected $table = "user_deals",
			  $fillable = ['user_id','deal_id'];
			  
	public $timestamps = false;

	

}