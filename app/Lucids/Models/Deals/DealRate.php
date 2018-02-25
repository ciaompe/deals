<?php

namespace Lucids\Models\Deals;

use Illuminate\Database\Eloquent\Model as Eloquent;

class DealRate extends Eloquent {

	protected $table = "deal_rates",
			  $fillable = ['user_id','rate'];
			  
	public $timestamps = false;

}