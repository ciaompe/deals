<?php

namespace Lucids\Models\Deals;

use Illuminate\Database\Eloquent\Model as Eloquent;


class Source extends Eloquent {

	protected $table = "sources",
			  $fillable = ['name', 'url', 'logo'];

	public $timestamps = false;

	public function getLogo($baseUrl, $imageUrl, $view = false) {
		if ($this->hasLogo()) {
			if (file_exists(APP_PATH.$this->logo)) {
				return $baseUrl.$this->logo;
			} else {
				if ($view) {
					return $imageUrl.'/deal_source_default.png';
				} else {
					return $baseUrl.$imageUrl.'/deal_source_default.png';
				}
			}
			
		} else {
			if ($view) {
				return $imageUrl.'/deal_source_default.png';
			} else {
				return $baseUrl.$imageUrl.'/deal_source_default.png';
			}
		}
	}

	public function hasLogo() {
		if ($this->logo != NULL || $this->logo != "") {
    		return true;
    	}
    	return false;
	}

	public function deals() {

		return $this->hasMany('Lucids\Models\Deals\Deal');

	}
}