<?php

use Carbon\Carbon;
use Lucids\Models\Auth\PasswordReset;
use Intervention\Image\ImageManagerStatic as Image;
use ReCaptcha\ReCaptcha;

/*
Account login GET
*/
$app->get('/admin/account/login', $adminAuth(false), function() use ($app) {

	$app->render('admin/account/login.php', [
		'showCapcha' => (isset($_SESSION['admin_login_attempt']) && $_SESSION['admin_login_attempt'] >= 3) ? true : false
	]);

})->name('admin_login');

/*
Account login POST
*/
$app->post('/admin/account/login', $adminAuth(false), function() use ($app) {
	
	$request = $app->request;
	$v = $app->validator;

	$v->validate([
		'email' => [$request->post('email'), 'required|email'],
		'password' => [$request->post('password'), 'required'],
	]);

	$showCapcha = (isset($_SESSION['admin_login_attempt']) && $_SESSION['admin_login_attempt'] >= 3) ? true : false;

	$recaptcha = new ReCaptcha($app->config->get('recaptcha.secret'));
	$resp = $recaptcha->verify($request->post('g-recaptcha-response'), $request->getIp());

	$validateCapcha = $showCapcha ? $resp->isSuccess() : true;

	if ($v->passes() && $validateCapcha) {

		$user = $app->user->
						where('email', $request->post('email'))
						->first();

		if ($user && $user->isBackend()) {

			if($app->hash->passwordCheck($request->post('password'), $user->password) && !$user->isBan() ) {

				$_SESSION[$app->config->get('auth.admin')] = $user->id;

				unset($_SESSION['admin_login_attempt']);

				$app->flash('success', 'Login successful ');
				$app->response->redirect($app->urlFor('admin_home'));

			} else {

				if (!isset($_SESSION['admin_login_attempt'])) {
					$_SESSION['admin_login_attempt'] = 1;
				} else {
					$_SESSION['admin_login_attempt'] += 1;
				}

				$app->flash('error', 'Authentication error, please try again');
				$app->response->redirect($app->urlFor('admin_login'));
			}
		} else {

			if (!isset($_SESSION['admin_login_attempt'])) {
				$_SESSION['admin_login_attempt'] = 1;
			} else {
				$_SESSION['admin_login_attempt'] += 1;
			}

			$app->flash('error', 'Authentication error, please try again');
			$app->response->redirect($app->urlFor('admin_login'));
		}
	}

	$app->render('admin/account/login.php', [
		'errors' => $v->errors(),
		'showCapcha' => $showCapcha,
		'capcha' => $resp->getErrorCodes()
	]);

});

/*
Auth GET Forgot Password
*/
$app->get('/admin/account/forgot', $adminAuth(false), function() use ($app) {

	$app->render('admin/account/forgot.php');

})->name('admin_forgot');

/*
Auth POST Forgot Password
*/
$app->post('/admin/account/forgot', $adminAuth(false), function() use ($app) {

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

			$app->mail->send('email/admin/forgot.php', ['user' => $user, 'token' => $token], function($message) use ($user) {
				$message->to($user->email);
				$message->subject('Password Reset Instruction');
			});

			$app->flash('success', 'Email sent successful, please find reset link in your email account and reset the password');
			$app->response->redirect($app->urlFor('admin_forgot'));
		
		}

	}

	$app->render('admin/account/forgot.php', [
		'errors' => $v->errors(),
		'request' => $request
	]);

});

/*
Auth GET Reset Password
*/
$app->get('/admin/account/reset', $adminAuth(false), function() use ($app) {

	$token = $app->hash->hash($app->request->get('token'));

	$reset = PasswordReset::where('token', $token)
							->where('created_at', '>=', Carbon::today() )
							->first();

	if ($reset) {

		$app->render('admin/account/reset.php', ['token' => $app->request->get('token') ]);
		
	} else {
		$app->flash('error', 'Invalid reset token');
		$app->response->redirect($app->urlFor('auth_forgot'));
	}

})->name('admin_reset');


/*
Auth POST Reset Password
*/
$app->post('/admin/account/reset', $adminAuth(false), function() use ($app) {

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

			$app->mail->send('email/admin/reset.php', [], function($message) use ($reset) {
				$message->to($reset->user->email);
				$message->subject('Password Reset Successfull');
			});

			$reset->delete();

			$app->flash('success', 'Password Reset Successfull, You can login now');
			$app->response->redirect($app->urlFor('admin_login'));
		}

		$app->render('admin/account/reset.php', [
			'errors' => $v->errors(),
			'token' => $request->get('token')
		]);
	}

});


/*
Account logout GET
*/
$app->get('/admin/account/logout', $adminAuth(true), function() use ($app){

	unset($_SESSION[$app->config->get('auth.admin')]);
	$app->response->redirect($app->urlFor('admin_login'));

})->name('admin_account_logout');



/*
Account change password GET
*/
$app->get('/admin/account/password', $adminAuth(true), function() use ($app) {

	$app->render('admin/account/changePassword.php');

})->name('admin_account_password');

/*
Account change password POST
*/
$app->post('/admin/account/password', $adminAuth(true), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$v->validate([
		'password' => [$request->post('password'), 'required'],
		'password_new' => [$request->post('password_new'), 'required|min(6)'],
		'password_confirm' => [$request->post('password_confirm'), 'required|matches(password_new)'],
	]);

	$v->addFieldMessages([
		'password_new' => [
	        'required' => 'new password is required',
	        'min' => 'password must be greater than 6 characters'
	    ],
	    'password_confirm' => [
	        'required' => 'confirm password is required',
	        'matches' => 'confirm password not matched with new password'
	    ]
	]);

	if ($v->passes()) {

		if($app->hash->passwordCheck($request->post('password'), $app->admin->password)) {

			$app->admin->update([
				'password' =>  $app->hash->passwordHash($request->post('password_new'))
			]);

			$app->mail->send('email/auth/change.php', [], function($message) use ($app) {
				$message->to($app->admin->email);
				$message->subject('Password Changed Successfull');
			});

			$app->flash('success', 'Password Changed Successful');
			$app->response->redirect($app->urlFor('admin_account_password'));

		} else {
			$app->flash('error', 'Current Passowrd is incorrect, Please try again');
			$app->response->redirect($app->urlFor('admin_account_password'));
		}
	}

	$app->render('/admin/account/changePassword.php', [
		'errors' => $v->errors()
	]);

});

/*
Account update GET
*/
$app->get('/admin/account/update', $adminAuth(true), function() use ($app) {

	$app->render('admin/account/update.php');

})->name('admin_account_update');

$app->post('/admin/account/update', $adminAuth(true), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$v->validate([
		'first_name' => [$request->post('f_name'), 'max(50)'],
		'last_name' => [$request->post('l_name'), 'max(50)'],
		'email' => [$request->post('email'), 'required|email|uniqueEmaiupdateUser('.$app->admin->email.')'],
		'password' => [$request->post('password'), 'required'],
	]);

	$v->addFieldMessages([
	    'first_name' => [
	        'max' => 'First name is too large',
	    ],
	    'last_name' => [
	        'max' => 'Last name is too large',
	    ]
	]);

	if ($v->passes()) {

		if($app->hash->passwordCheck($request->post('password'), $app->admin->password)) {

			$app->admin->update([
				'f_name' => $request->post('first_name'),
				'l_name' => $request->post('last_name'),
				'email' => $request->post('email')
			]);
			
			$app->flash('success', 'Account Updated Successful');
			$app->response->redirect($app->urlFor('admin_account_update'));

		} else {
			$app->flash('error', 'Current Passowrd is incorrect, Please try again');
			$app->response->redirect($app->urlFor('admin_account_update'));
		}
	}

	$app->render('admin/account/update.php', [
		'errors' => $v->errors(),
		'request' => $request
	]);

});

$app->post('/admin/account/propic', $adminAuth(true), function() use ($app) {

	$imgdata = json_decode($app->request->post('avatar_data'));
	$handle = new upload($_FILES['avatar_file']);

	if ($handle->uploaded) {

	  $path = APP_PATH.$app->config->get('app.avatar').$app->admin->id.'/';

	  $handle->file_new_name_body = uniqid(time()+3600);
	  $handle->allowed = array('image/*');
	  $handle->forbidden = array('application/*');
	  $handle->file_max_size = '2000000';
	  $handle->process($path);

	  if ($handle->processed) {

	  	$name = $app->hash->hash($app->randomlib->generateString(32));
	  	
	  	$file = $app->config->get('app.url').$app->config->get('app.avatar').$app->admin->id.'/'.$handle->file_dst_name_body.'.'.$handle->file_dst_name_ext;
	  	
	  	if ($app->admin->hasAvatar() && file_exists(APP_PATH.$app->admin->avatar)) {
	  		unlink(APP_PATH.$app->admin->avatar);
	  	}

	  	$background = Image::canvas(200, 200, '#ffffff');

	  	$image = Image::make($file)
	  		->crop(round($imgdata->width), round($imgdata->height), round($imgdata->x), round($imgdata->y))
	  		->resize(200, 200, function ($c) {
				    $c->aspectRatio();
				    $c->upsize();
			});

	  	$background->insert($image, 'center');	
	  	$background->save($path.$name.'.jpg');

	  	unlink($handle->file_dst_pathname);
	  	$handle->clean();
	  
	  
	  	$app->admin->update([
	  		'avatar' => $app->config->get('app.avatar').$app->admin->id.'/'.$name.'.jpg'
	  	]);

	  	echo json_encode(array(
            'state'  => 200,
            'message' => 'Uploaded Successful',
            'result' => $app->config->get('app.url').$app->config->get('app.avatar').$app->admin->id.'/'.$name.'.jpg'      
        ));

	  } else {
	  	echo json_encode(array(
			'state' => 200,
			'message' =>  $handle->error
		));
		$handle->clean();
	  }

	}

})->name('admin_account_propic');

