<?php

namespace Lucids\Helpers;

use Violin\Violin;
use Lucids\Models\Auth\User;
use Lucids\Models\Deals\Source;
use Lucids\Models\Deals\Category;
use Lucids\Models\Advertise\Adsize;

/**
* Validator Helper class for lucids development app
*/

class Validator extends Violin
{

	public function __construct() {

		$this->addRuleMessage('uniqueEmail', 'Email address is already taken ');
		
		$this->addRuleMessage('uniqueEmailUser', 'Email address is already taken ');

		$this->addRuleMessage('uniqueUsername', 'Username is already taken ');

		$this->addRuleMessage('uniqueEmaiupdateUser', 'Email address is already taken ');

		$this->addRuleMessage('uniqueSourceName', 'Source name is already exists');
		
		$this->addRuleMessage('source', 'Source is invalid');

		$this->addRuleMessage('dealType', 'Deal Type is invalid');

		$this->addRuleMessage('adType', 'Ad Type is invalid');

		$this->addRuleMessage('adSize', 'Ad Size is invalid');
	}


	public function validate_uniqueEmailUser($value, $input, $args){

		$user = User::where('email', '=', $value)->count();

		if ($user) {
			return false;
		}

		return true;
	}
	public function validate_uniqueEmaiupdateUser($value, $input, $args)
	{
		if (!empty($args) && $args[0] == $value) {
			return true;
		}

		$user = User::where('email', '=', $value)->count();

		if ($user) {
			return false;
		}

		return true;
	}

	public function validate_uniqueUsername($value, $input, $args) {

		$user = User::where('username', '=', $value)->count();

		if ($user) {
			return false;
		}

		return true;
	}

	public function validate_uniqueSourceName($value, $input, $args) {

		if (!empty($args) && $args[0] == $value) {
			return true;
		}

		$source = Source::where('name', '=', $value)->count();

		if ($source) {
			return false;
		}

		return true;
	}

	public function validate_source($value, $input, $args) {

		$source = Source::where('id', $value)->count();

		if ($source) {
			return true;
		}

		return false;
	}

	public function validate_dealType($value, $input, $args) {
		if ($value == "fixed") {
			return true;
		} else if($value == "count") {
			return true;
		}

		return false;
	}

	public function validate_adType($value, $input, $args) {

		if ($value == "code") {
			return true;
		} else if($value == "image") {
			return true;
		}
		return false;
	}

	public function validate_adSize($value, $input, $args) {

		$adsize = Adsize::where('id', $value)->count();
		
		if ($adsize) {
			return true;
		}
		return false;
	}
}