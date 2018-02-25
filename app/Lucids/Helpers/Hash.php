<?php

namespace Lucids\Helpers;

/**
* Hash and Password Hashing helper for lucids development app
*/

class Hash 
{	
	protected $_config;
	
	public function __construct($config)
	{
		$this->_config = $config;
	}

	public function passwordHash($password) {
		//returning user enterd password hash
		return password_hash(
			$password,
			$this->_config->get('hash.algo'),
			['cost' => $this->_config->get('hash.cost')]
		);
	}

	public function passwordCheck($password, $hash) {
		
		//returning user enterd password verification true or false
		return password_verify($password, $hash);
	}

	public function hash($input) {
		return hash('sha256', $input);
	}

	public function hashCheck($known_string, $user_string) {
		if(!function_exists('hash_equals')) {
			return $this->hash_equals($known_string, $user_string);
		}
		return hash_equals( $known_string , $user_string );
	}

	private function hash_equals($str1, $str2) {

		if(strlen($str1) != strlen($str2)) {
		    return false;
		} else {
		    $res = $str1 ^ $str2;
		    $ret = 0;
		    for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
		    return !$ret;
		}
	}
 
}