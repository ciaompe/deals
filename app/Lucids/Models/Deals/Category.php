<?php

namespace Lucids\Models\Deals;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Category extends Eloquent {

	protected $table = "categories",
			  $fillable = ['parent', 'depth', 'name', 'dis', 'image', 'slug'];  

	public $timestamps = false;

	public function deals() {
		return $this->belongsToMany('Lucids\Models\Deals\Deal');
	}

	public function setNameAttribute($value){
	    $this->attributes['name'] = $value;
	    $this->attributes['slug'] = $this->slug($value);
	}

	private function slug($name) {
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $name);

		// trim
		$text = trim($text, '-');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
		    return 'n-a';
		}

		return $text;
	}

	public function hasImage() {
		if ($this->image != NULL || $this->image != "") {
    		return true;
    	}
    	return false;
	}

	public function getImage($baseUrl, $imageUrl, $view = false) {
		if ($this->hasImage()) {
			if (file_exists(APP_PATH.$this->image)) {
				return $baseUrl.$this->image;
			} else {
				if ($view) {
					return $imageUrl.'/category_default.png';
				} else {
					return $baseUrl.$imageUrl.'/category_default.png';
				}
			}
			
		} else {
			if ($view) {
				return $imageUrl.'/category_default.png';
			} else {
				return $baseUrl.$imageUrl.'/category_default.png';
			}
		}
	}
}