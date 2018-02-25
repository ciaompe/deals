<?php

namespace Lucids\Models\Deals;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Carbon\Carbon;
use \Eventviva\ImageResize;

class Deal extends Eloquent {

	protected $table = "deals",
			  $fillable = ['source_id', 'title', 'description', 'price', 'discount', 'url', 'type', 'expirey', 'featured'];  

	public function categories() {
		return $this->belongsToMany('Lucids\Models\Deals\Category');
	}

	public function images() {
		return $this->hasMany('Lucids\Models\Deals\DealImage');
	}

	public function source() {
		return $this->belongsTo('Lucids\Models\Deals\Source');
	}

	public function getSourceName() {
		return $this->source->name;
	}

	public function checkImagesisExists() {

		$images = array();

		foreach ($this->images as $image) {

			if (file_exists(APP_PATH.$image->image.'.jpg')) {
				$images[] = array(
					'id' => $image->id,
					'image' => $image->image
				);
			}
			
		}

		return $images;
	}

	public function getImageForTable($url, $imgDir) {

		if ($this->images->count()) {
			if (file_exists(APP_PATH.$this->images[0]->image.'.jpg')) {
				return $url.$this->images[0]->image.'_thumbnail.jpg';
			}
		}
    		
    	return $url.$imgDir.'/deal_default.png';
	}

	public function getExpirey() {

		if ($this->type == "count") {
			if (Carbon::createFromFormat('Y-m-d H:i:s', $this->expirey) > Carbon::now()) {
				return Carbon::createFromFormat('Y-m-d H:i:s', $this->expirey)->diffForHumans();
			} else {
				return 'Deal Expired';
			}
		} else {
			return '';
		}
	}

	public function getCateoriesForTable() {

		$categories = "";

		if ($this->categories->count()) {

			foreach ($this->categories as $category) {
				$categories[] = $category->name;
			}
			return implode(', ', $categories);

		}
	}


	public function getDealImage($baseUrl, $imageUrl, $size = null, $view = false) {

		if ($this->images->count()) {

			if (file_exists(APP_PATH.$this->images[0]->image.'.jpg')) {

				if ($size == "small") {
					return $baseUrl.$this->images[0]->image.'_thumbnail.jpg';
				} else if($size == "medium"){
					return $baseUrl.$this->images[0]->image.'_medium.jpg';
				} else if($size == "large") {
					return $baseUrl.$this->images[0]->image.'.jpg';
				}
			
			} else {
				if ($view) {
					if ($size == "small") {
						return $imageUrl.'/deal_default_small.png';
					} else if($size == "medium"){
						return $imageUrl.'/deal_default_medium.png';
					} else if($size == "large") {
						return $imageUrl.'/deal_default_large.png';
					}
				} else {
					if ($size == "small") {
						return $baseUrl.$imageUrl.'/deal_default_small.png';
					} else if($size == "medium"){
						return $baseUrl.$imageUrl.'/deal_default_medium.png';
					} else if($size == "large") {
						return $baseUrl.$imageUrl.'/deal_default_large.png';
					}
				}
			}

		} else {

			if ($view) {
				if ($size == "small") {
					return $imageUrl.'/deal_default_small.png';
				} else if($size == "medium"){
					return $imageUrl.'/deal_default_medium.png';
				} else if($size == "large") {
					return $imageUrl.'/deal_default_large.png';
				}
			} else {
				if ($size == "small") {
					return $baseUrl.$imageUrl.'/deal_default_small.png';
				} else if($size == "medium"){
					return $baseUrl.$imageUrl.'/deal_default_medium.png';
				} else if($size == "large") {
					return $baseUrl.$imageUrl.'/deal_default_large.png';
				}
			}
		}
	}

	public function getDealImages($baseUrl, $imageUrl, $view = false) {

		$images = [];

		if ($this->images->count()) {

			foreach ($this->images as $img) {

				if (file_exists(APP_PATH.$img->image.'.jpg')) {
					$images[] = $baseUrl.$img->image;
				} else {
					if ($view) {
						$images[] = $imageUrl.'/deal_default.png';
					} else {
						$images[] = $baseUrl.$imageUrl.'/deal_default.png';
					}
				}

			}
			return $images;
		} else {

			if ($view) {
				return  $imageUrl.'/deal_default.png';
			} else {
				return  $baseUrl.$imageUrl.'/deal_default.png';
			}
		}
	}


	public function dealUrl() {
		 // replace non letter or digits by -
		  $text = preg_replace('~[^\\pL\d]+~u', '-', $this->title);

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

	public function getDiscount() {

		if ($this->discount != NULL) {
			return $this->price - ($this->price * ($this->discount / 100) );
		} else {
			return $this->price;
		}
	}

	public function rates() {
		return $this->hasMany('Lucids\Models\Deals\DealRate');
	}

	public function likesCount() {

		$count = 0;

		if ($this->rates->count()) {
			
			foreach ($this->rates as $rate) {
				if ($rate->rate == 1) {
					$count = $count + 1;
				}
			}
		}

		return $this->knumber($count);
	}

	public function dislikesCount() {

		$count = 0;

		if ($this->rates->count()) {
			
			foreach ($this->rates as $rate) {
				if ($rate->rate == 2) {
					$count = $count + 1;
				}
			}
		}

		return $this->knumber($count);
	}

	private function knumber($number) {
	    if($number >= 1000) {
	       return $number/1000 . "k";
	    }
	    else {
	        return $number;
	    }
	}

	public function liked($userid) {

		if ($this->rates->count()) {
			foreach ($this->rates as $rate) {
				if ($rate->rate == 1 && $rate->user_id == $userid) {
					return true;
				}
			}
		}

		return false;
	}

	public function disLiked($userid) {

		if ($this->rates->count()) {
			foreach ($this->rates as $rate) {
				if ($rate->rate == 2 && $rate->user_id == $userid) {
					return true;
				}
			}
		}

		return false;
	}

	public function scopeExpirey($query) {
		return $query->where('expirey', '>', Carbon::now())->orWhere('expirey', '=', NULL);
	}

	public function comments() {
		return $this->hasMany('Lucids\Models\Deals\DealComment');
	}

	public function commentCount() {
	    return $this->knumber($this->comments()->where('status', 1)->count());
	}

	public function users() {
		return $this->hasMany('Lucids\Models\Deals\UserDeal');
	}

	public function isNew() {

		if ($this->created_at >=Carbon::yesterday()) {
			return true;
		}

		return false;
	}

	public function limiText($string, $num) {
		return (strlen($string) > $num) ? substr($string,0,$num).'...' : $string;
	}

	public function getDiscription() {
		return base64_decode($this->description);
	}

	public function scopeExpires($query) {
		return $query->where('type', 'count')->where('expirey', '>', Carbon::now())->orderBy('expirey', 'ASC');
	}

	public function visit($url){

       $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
       $ch=curl_init();
       curl_setopt ($ch, CURLOPT_URL,$url );
       curl_setopt($ch, CURLOPT_USERAGENT, $agent);
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt ($ch,CURLOPT_VERBOSE,false);
       curl_setopt($ch, CURLOPT_TIMEOUT, 5);
       curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
       curl_setopt($ch,CURLOPT_SSLVERSION,3);
       curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
       $page=curl_exec($ch);
       $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       curl_close($ch);

       if($httpcode>=200 && $httpcode<300) {

       		return true;
       }

       	return false;
	}

	public function getCountDown() {
		return ($this->type == "count") ? date("m/d/Y H:i:s", strtotime($this->expirey) ) : NULL;
	}

	public function createdAt() {
    	return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->diffForHumans();
    }

    public function updatedAt() {
    	return Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at)->diffForHumans();
    }


    public function discriptionText() {
    	$text = base64_decode($this->description);

    	return strip_tags($text);
    }

}