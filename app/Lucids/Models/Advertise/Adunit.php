<?php

namespace Lucids\Models\Advertise;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Adunit extends Eloquent {

	protected $table = "adunits",
			$fillable = ['type', 'adsize_id', 'image', 'url', 'code'];

	public function adsize() {
		return $this->belongsTo('Lucids\Models\Advertise\Adsize');
	}

	public function getImage($baseUrl, $imageUrl, $view = false) {

		if ($this->hasImage()) {
			if (file_exists(APP_PATH.$this->image)) {
				return $baseUrl.$this->image;
			} else {
				if ($view) {
					return $imageUrl.'/ad_default.png';
				} else {
					return $baseUrl.$imageUrl.'/ad_default.png';
				}
			}
			
		} else {
			if ($view) {
				return $imageUrl.'/ad_default.png';
			} else {
				return $baseUrl.$imageUrl.'/ad_default.png';
			}
		}
	}

	public function hasImage() {
		if ($this->image != NULL || $this->image != "") {
    		return true;
    	}
    	return false;
	}

	public function getCode() {
		return base64_decode($this->code);
	}

}