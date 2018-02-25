<?php

namespace Lucids\Models\Auth;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent {

	protected $fillable = [
		'email',
		'f_name', 
		'l_name', 
		'password', 
		'avatar', 
		'activate', 
		'identifier',
		'activate_hash',
		'remember_token'
	];

	public function getName(){

		if (!$this->f_name || !$this->l_name) {
			return $this->email;
		}
		return $this->f_name.' '.$this->l_name;
	}

	public function isAdmin() {
		return (bool) $this->permissions->is_admin;
	}

	public function isStaff() {
		return (bool) $this->permissions->is_staff;
	}

	public function isBackend() {
		if ($this->permissions->is_admin || $this->permissions->is_staff) {
			return true;
		}
		return false;
	}

	public function permissions() {
		return $this->hasOne('Lucids\Models\Auth\UserPermission');
	}

	public function passwordReset() {
		return $this->hasOne('Lucids\Models\Auth\PasswordReset');
	}

	public function deleteHasToken() {

		$token = $this->passwordReset();

		if ($token->count()) {
			$token->delete();
		}
	}

	public function isLocalUser() {
		if ($this->identifier == NULL || $this->identifier == "") {
			return true;
		}
		return false;
	}

	public function isActivated() {
        if($this->activate != false) {
            return true;
        }
        return false;
    }

    public function isBan() {
    	return (bool) $this->permissions->is_ban;
    }

    public function hasAvatar() {
    	if ($this->avatar != NULL || $this->avatar != "") {
    		return true;
    	}
    	return false;
    }

    public function userType() {
    	if ($this->isAdmin()) {
    		return 'Admin';
    	} elseif ($this->isStaff()) {
    		return 'Staff';
    	} else {
    		return 'User';
    	}
    }
    public function getAvatar($url, $imgDir, $view = false) {
    	if ($this->isLocalUser()) {
    		if ($this->hasAvatar()) {
    			if (file_exists(APP_PATH.$this->avatar)) {
    				return $url.$this->avatar;
    			} else {
    				if ($view) {
	    				return $imgDir.'/default.jpg';
	    			} else {
	    				return $url.$imgDir.'/default.jpg';
	    			}
    			}
    		} else {
    			if ($view) {
    				return $imgDir.'/default.jpg';
    			} else {
    				return $url.$imgDir.'/default.jpg';
    			}
    		}
    	} else {
    		return $this->avatar;
    	}
    }

    public function deals() {
    	return $this->hasMany('Lucids\Models\Deals\UserDeal');
    }

    public function inList($deal_id) {
    	foreach ($this->deals as $deal) {
    		if($deal->deal_id == $deal_id) {
    			return true;
    		}
    	}

    	return false;
    }

    public function userDeals() {
		return $this->belongsToMany('Lucids\Models\Deals\Deal','user_deals','user_id','deal_id');
	}

	public function comments() {
		return $this->hasMany('Lucids\Models\Deals\DealComment');
	}

	public function rates() {
		return $this->hasMany('Lucids\Models\Deals\DealRate');
	}

	public function joined() {
		return $this->created_at->diffForHumans();
	}

	public function commentCount() {
		return $this->comments->count();
	}

	public function watchlistCount() {
		return $this->userDeals->count();
	}

}