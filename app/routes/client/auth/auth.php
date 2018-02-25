<?php

use Carbon\Carbon;
use Lucids\Models\Auth\UserPermission;
use Lucids\Models\Auth\PasswordReset;
use ReCaptcha\ReCaptcha;

/*
Auth Login get
*/
$app->get('/auth/login', $authCheck(false), function() use($app) {

	if ($app->request()->get('r')) {
     	 $_SESSION['urlRedirect'] = $app->request()->get('r');
  	}

	$app->render('client/auth/login.php', [
		'showCapcha' => (isset($_SESSION['login_attempt']) && $_SESSION['login_attempt'] >= 3) ? true : false
	]);

})->name('auth_login');

/*
Auth Login post
*/
$app->post('/auth/login', $authCheck(false), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$v->validate([
		'identifier' => [$request->post('identifier'), 'required'],
		'password' => [$request->post('password'), 'required'],
	]);

	$v->addFieldMessages([
	    'identifier' => [
	        'required' => 'email is required'
	    ]
	]);

	$showCapcha = (isset($_SESSION['login_attempt']) && $_SESSION['login_attempt'] >= 3) ? true : false;

	$recaptcha = new ReCaptcha($app->config->get('recaptcha.secret'));
	$resp = $recaptcha->verify($request->post('g-recaptcha-response'), $request->getIp());

	$validateCapcha = $showCapcha ? $resp->isSuccess() : true;

	if ($v->passes() && $validateCapcha) {

		$user = $app->user->where('email', $request->post('identifier'))->first();

		if ($user && $app->hash->passwordCheck($request->post('password'), $user->password)) {

			if (!$user->isBan()) {

				$_SESSION[$app->config->get('auth.session')] = $user->id;

				if ($request->post('remember_me') == 'on') {
					
					$remember_token = $app->randomlib->generateString(128);

					$user->update([
						'remember_token' => $app->hash->hash($remember_token)
					]);

					$app->setCookie(
						$app->config->get('auth.remember'),
						"{$remember_token}",
						Carbon::parse('+4 week')->timestamp
					);
				}
				
				unset($_SESSION['login_attempt']);

				$app->flash('success', 'Login successful ');

				if (isset($_SESSION['urlRedirect'])) {
			       $tmp = $_SESSION['urlRedirect'];
			       unset($_SESSION['urlRedirect']);
			       $app->redirect($tmp);

			    } else {
			    	$app->response->redirect($app->urlFor('home'));
			    }

			} else {

				$app->flash('error', 'Your account is banned, please contact administrative team');
		      	$app->response->redirect($app->urlFor('auth_login'));
			}

		} else {

			if (!isset($_SESSION['login_attempt'])) {
				$_SESSION['login_attempt'] = 1;
			} else {
				$_SESSION['login_attempt'] += 1;
			}

			$app->flash('error', 'Authentication error, please try again');
			$app->response->redirect($app->urlFor('auth_login'));
		}

	}

	$app->render('client/auth/login.php', [
		'errors' => $v->errors(),
		'request' => $request,
		'showCapcha' => $showCapcha,
		'capcha' => $resp->getErrorCodes()
	]);

});

/*
Auth Register get
*/
$app->get('/auth/register', $authCheck(false), function() use($app) {

	$app->render('client/auth/register.php');

})->name('auth_register');

/*
Auth Register post
*/
$app->post('/auth/register', $authCheck(false), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$v->validate([
		'email' => [$request->post('email'), 'required|email|uniqueEmailUser'],
		'password' => [$request->post('password'), 'required|min(6)'],
		'password_confirm' => [$request->post('password_confirm'), 'required|matches(password)'],
	]);

	$v->addFieldMessages([
	    'password_confirm' => [
	        'required' => 'password confirm is required',
	        'matches' => 'password confirm not matched with password'
	    ]
	]);

	$recaptcha = new ReCaptcha($app->config->get('recaptcha.secret'));
	$resp = $recaptcha->verify($request->post('g-recaptcha-response'), $request->getIp());

	if ($v->passes() && $resp->isSuccess()) {

		//$activate_hash = $app->randomlib->generateString(128);

		$user = $app->user->create([
			'email' => $request->post('email'),
			'password' => $app->hash->passwordHash($request->post('password')),
			'activate' => 1
		]);

		$user->permissions()->create(UserPermission::$defaults);

		$app->mail->send('email/auth/registration.php', ['user' => $user], function($message) use ($user) {
			$message->to($user->email);
			$message->subject('Registration Successful');
		});

		// $app->mail->send('email/auth/activation.php', ['user' => $user, 'activate_hash' => $activate_hash], function($message) use ($user) {
		// 	$message->to($user->email);
		// 	$message->subject('Account Activation');
		// });
		
		$app->flash('success', 'Registration successful, now you can login');
		$app->response->redirect($app->urlFor('auth_login'));

	} 

	$app->render('client/auth/register.php', [
		'errors' => $v->errors(),
		'request' => $request,
		'capcha' => $resp->getErrorCodes()
	]);

});

/*
Auth Activate
*/
$app->get('/auth/activate', function() use ($app) {

	$request = $app->request;

	$email = $request->get('email');
	$token = $request->get('token');

	if ($email != null && $token != null) {
		
		$token = $app->hash->hash($token);

		$user = $app->user->where('email', $email)->where('activate', false)->first();

		if ($user && $app->hash->hashCheck($user->activate_hash, $token)) {
			$user->update([
				'activate' => 1,
				'activate_hash' => NULL
			]);

			$app->flash('success', 'Account activation is successful');
			$app->response->redirect($app->urlFor('home'));

		} else {

			$app->flash('error', 'User identification is faild');
			$app->response->redirect($app->urlFor('home'));
		}
	} 
	else
	{
		$app->flash('error', 'User identification is faild');
		$app->response->redirect($app->urlFor('home'));
	}

})->name('auth_activate');


/*
Auth GET Resend activatiion code
*/
$app->get('/auth/resend', function() use($app) {

	$app->render('client/auth/resend.php');

})->name('auth_resend');

/*
Auth POST Resend activatiion code
*/
$app->Post('/auth/resend', function() use($app) {

	$request = $app->request;

	$v = $app->validator;

	$v->validate([
		'email' => [$request->post('email'), 'required|email']
	]);

	if($v->passes()) {

		$user = $app->user->where('email', $request->post('email'))->where('activate', 0)->first();

		if ($user) {

			$activate_hash = $app->randomlib->generateString(128);

			$user->update([
				'activate_hash' => $app->hash->hash($activate_hash)
			]);

			$app->mail->send('email/auth/activation.php', ['user' => $user, 'activate_hash' => $activate_hash], function($message) use ($user) {
				$message->to($user->email);
				$message->subject('Account Activation');
			});

			$app->flash('success', 'Activation code sent successful, please log into your email and activate your account');
			$app->response->redirect($app->urlFor('home'));
			
		} else {
			$app->flash('error', 'Email address not found');
			$app->response->redirect($app->urlFor('auth_resend'));
		}

	}

	$app->render('client/auth/resend.php', [
		'errors' => $v->errors()
	]);	

});


/*
Auth Logout
*/
$app->get('/auth/logout', $authCheck(true), function() use($app) {

	unset($_SESSION[$app->config->get('auth.session')]);

	if ($app->getCookie($app->config->get('auth.remember')) ){

		$app->user->update([
			'remember_token' => NULL
		]);

		$app->deleteCookie($app->config->get('auth.remember'));
	}

	$app->response->redirect($app->urlFor('home'));

})->name('auth_logout');


/*
Auth GET Forgot Password
*/
$app->get('/auth/forgot', $authCheck(false), function() use ($app) {

	$app->render('client/auth/forgot.php');

})->name('auth_forgot');

/*
Auth POST Forgot Password
*/
$app->post('/auth/forgot', $authCheck(false), function() use ($app) {

	$request = $app->request;

	$v = $app->validator;

	$v->validate([
		'email' => [$request->post('email'), 'required|email']
	]);

	if ($v->passes()) {
		
		$user = $app->user->where('email', $request->post('email'))->first();
		
		if ($user && $user->isLocalUser()) {

			$token = $app->randomlib->generateString(128);

			$user->deleteHasToken();

			$user->passwordReset()->create([
				'token' => $app->hash->hash($token)
			]);

			$app->mail->send('email/auth/forgot.php', ['user' => $user, 'token' => $token], function($message) use ($user) {
				$message->to($user->email);
				$message->subject('Password Reset Instruction');
			});

			$app->flash('success', 'Email sent successful, please find reset link in your email account and reset the password');
			$app->response->redirect($app->urlFor('auth_forgot'));
		
		}
	}

	$app->render('client/auth/forgot.php', [
		'errors' => $v->errors(),
		'request' => $request
	]);

});

/*
Auth GET Reset Password
*/
$app->get('/auth/reset', $authCheck(false), function() use ($app) {

	$token = $app->hash->hash($app->request->get('token'));

	$reset = PasswordReset::where('token', $token)
							->where('created_at', '>=', Carbon::today() )
							->first();

	if ($reset) {

		$app->render('client/auth/reset.php', ['token' => $app->request->get('token') ]);
		
	} else {
		$app->flash('error', 'Invalid reset token');
		$app->response->redirect($app->urlFor('auth_forgot'));
	}

})->name('auth_reset');


/*
Auth POST Reset Password
*/
$app->post('/auth/reset', $authCheck(false), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$token = $app->hash->hash($request->get('token'));

	$reset = PasswordReset::where('token', $token)
								->where('created_at', '>=', Carbon::today() )
								->first();
	if ($reset) {

		$v->addFieldMessages([
		    'password_confirm' => [
		        'required' => 'password confirm is required',
		        'matches' => 'password confirm not matched with password'
		    ]
		]);
		
		$v->validate([
			'password' => [$request->post('password'), 'required|min(6)'],
			'password_confirm' => [$request->post('password_confirm'), 'required|matches(password)']
		]);

		if ($v->passes()) {

			$reset->user()->update([
				'password' =>  $app->hash->passwordHash($request->post('password'))
			]);

			$app->mail->send('email/auth/reset.php', [], function($message) use ($reset) {
				$message->to($reset->user->email);
				$message->subject('Password Reset Successfull');
			});

			$reset->delete();

			$app->flash('success', 'Password Reset Successfull, You can login now');
			$app->response->redirect($app->urlFor('auth_login'));
		}

		$app->render('client/auth/reset.php', [
			'errors' => $v->errors(),
			'token' => $request->get('token')
		]);
	}

});

/*
Auth Socail Login
*/
$app->get('/auth/social/:provider', $authCheck(false), function($provider) use($app) {
	
	if ($provider == "Facebook" || $provider == "Twitter" || $provider == "Google") {
		
		$authProvider = $app->hybridAuth->authenticate($provider);
		$profile = $authProvider->getUserProfile();

		$user = $app->user->where('identifier', '=', $profile->identifier)->first();

		if ($user) {

			if (!$user->isBan()) {

				$remember_token = $app->randomlib->generateString(128);

				$user->update([
					'remember_token' =>$app->hash->hash($remember_token)
				]);

				$_SESSION[$app->config->get('auth.session')] = $user->id;

				$app->setCookie(
					$app->config->get('auth.remember'),
					"{$remember_token}",
					Carbon::parse('+4 week')->timestamp
				);

				$app->flash('success', 'Login Successfull');

				if (isset($_SESSION['urlRedirect'])) {
			       $tmp = $_SESSION['urlRedirect'];
			       unset($_SESSION['urlRedirect']);
			       $app->redirect($tmp);

			    } else {
			    	$app->response->redirect($app->urlFor('home'));
			    }

			} else {
				$app->flash('error', 'Your account is banned, please contact administrative team');
		      	$app->response->redirect($app->urlFor('auth_login'));
			}
				
		} else {

			$remember_token = $app->randomlib->generateString(128);

			$user = $app->user->create([
				'f_name' => $profile->firstName,
				'l_name' =>  $profile->lastName,
				'avatar' => $profile->photoURL,
				'activate' => 1,
				'identifier' => $profile->identifier,
				'remember_token' => $app->hash->hash($remember_token)
			]);
			$user->permissions()->create(UserPermission::$defaults);

			$_SESSION[$app->config->get('auth.session')] = $user->id;

			$app->setCookie(
				$app->config->get('auth.remember'),
				"{$remember_token}",
				Carbon::parse('+4 week')->timestamp
			);
			
			$app->flash('success', 'Registration Successfull');

			if (isset($_SESSION['urlRedirect'])) {
		       $tmp = $_SESSION['urlRedirect'];
		       unset($_SESSION['urlRedirect']);
		       $app->redirect($tmp);

		    } else {
		    	$app->response->redirect($app->urlFor('home'));
		    }
			
		}

	} else {
		$app->response->redirect($app->urlFor('home'));
	}

})->name('auth_social');