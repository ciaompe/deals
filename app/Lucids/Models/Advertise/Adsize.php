<?php

namespace Lucids\Models\Advertise;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Adsize extends Eloquent {

	protected $table = "adsizes";

	public function adunit() {
		return $this->hasOne('Lucids\Models\Advertise\Adunit');
	}

}