<?php

namespace Lucids\Middleware;
use Slim\Middleware;

/**
* This middleware is used to get user_id, user_identifier from the session and set user data to the application
*/

class BeforeMiddleware extends Middleware
{
	
	public function call(){	
		
		//hooking checkAuth method to the slim.before middleware
		$this->app->hook('slim.before', [$this, 'checkAuth'] );

		$this->next->call();
	}

	public function checkAuth() {

		//call default login method to the main call method
		$this->login();

		//call admin login check method to the main call method
		$this->adminAuth();

		//call remember me method to the main call method
		$this->rememberMe();

		//generating url to without directorys
		$url = parse_url($this->app->config->get('app.url'));
		
		//call parseData method to the main call method
		$this->parseData([
			'main_config' => $this->app->config->get('app'),
			'config' => $this->app->config->get('images'),
			'auth' => $this->app->auth,
			'admin' => $this->app->admin,
			'baseUrl' => $this->app->config->get('app.url'),
			'url' =>  $url['scheme'].'://'.$url['host'],
			'css'=> $this->app->config->get('app.url').$this->app->config->get('app.css'),
			'js'=> $this->app->config->get('app.url').$this->app->config->get('app.js'),
			'images' => $this->app->config->get('app.url').$this->app->config->get('app.images'),
			'tmp' => $this->app->config->get('app.url').$this->app->config->get('app.tmp'),
			'sitekey' => $this->app->config->get('recaptcha.siteKey'),
		]);

	}

	private function login() {
		//checking is set auth session
		if (isset($_SESSION[$this->app->config->get('auth.session')])) {
			//find user data and set to the app auth
			$user = $this->app->user->find($_SESSION[$this->app->config->get('auth.session')]);

			if ($user && !$user->isBan()) {
				$this->app->auth = $user;
			}
		}
	}

	private function adminAuth() {
		//checking is set auth admin session
		if (isset($_SESSION[$this->app->config->get('auth.admin')])) {
			$user = $this->app->user->find($_SESSION[$this->app->config->get('auth.admin')]);

			if ($user && $user->isBackend() && !$user->isBan()) {
				$this->app->admin = $user;
			} else {
				unset($_SESSION[$this->app->config->get('auth.admin')]);
			}
			
		}
	}

	private function rememberMe(){

		if ($this->app->getCookie($this->app->config->get('auth.remember')) && !$this->app->auth) {
			
			$remember_token = $this->app->hash->hash($this->app->getCookie($this->app->config->get('auth.remember')));

			$user = $this->app->user->where('remember_token', $remember_token)->first();

			if ($user && !$user->isBan()) {
				$_SESSION[$this->app->config->get('auth.session')] = $user->id;
				$this->app->auth = $user;
			} 
		}
	}

	private function parseData($data) {
		//parsing data to the slim view
		$this->app->view()->appendData($data);
	}

}