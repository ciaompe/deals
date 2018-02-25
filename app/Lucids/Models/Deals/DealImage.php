<?php

namespace Lucids\Models\Deals;

use Illuminate\Database\Eloquent\Model as Eloquent;

class DealImage extends Eloquent {

	protected $table = "deal_images",
			  $fillable = ['image'];
			  
	public $timestamps = false;

}