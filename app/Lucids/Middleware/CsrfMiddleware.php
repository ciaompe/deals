<?php

namespace Lucids\Middleware;

use Slim\Middleware;
use \Exception;

/*
This middelware is used to protect forms from CSRF Attacks
*/

class CsrfMiddleware extends Middleware {

	public function call()
	{
		$this->app->hook('slim.before', [$this, 'check'] );
		$this->next->call();
	}

	public function check() {

		if (!isset($_SESSION['csrf_token']) ) {
			
			$_SESSION['csrf_token'] = $this->app->hash->hash($this->app->randomlib->generateString(64));
		}

		$token = $_SESSION['csrf_token'];

		if ( in_array($this->app->request->getMethod(), ['POST', 'PUT', 'DELETE']) ) {

			$form_token = $this->app->request->post('csrf_token') ? : '';

			if (!$this->app->hash->hashCheck($token, $form_token) ) {
				throw new Exception("CSRF Token Mismatch");
			}
		}

		$this->parseData([
			'csrf_token' => $token
		]);
	}

	public function parseData($data) {
		//parsing data to the slim view
		$this->app->view()->appendData($data);
	}
}
