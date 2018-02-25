<?php

use Intervention\Image\ImageManagerStatic as Image;

$app->get('/account/', $authCheck(true), function() use ($app) {

	$page = $app->request->get('page');
	$per_page = $app->config->get('perpage.watchlist');

	$page =  (isset($page) && is_numeric($page) ) ? $page : 1;

	$count = $app->auth->userDeals->count();

	$start = ($page-1) * $per_page;

	$app->render('client/auth/account/myaccount.php', [
		'deals' => $app->auth->userDeals()->limit($per_page)->offset($start)->orderBy('deals.id', 'DESC')->get(),
		'page' => $page,
		'pages' => ceil($count / $per_page)
	]);

})->name('myaccount');


$app->post('/account/update', $authCheck(true), $activated(), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	if ($app->auth->isLocalUser()) {
		$v->validate([
			'first_name' => [$request->post('f_name'), 'max(50)'],
			'last_name' => [$request->post('l_name'), 'max(50)'],
			'email' => [$request->post('email'), 'required|email|uniqueEmaiupdateUser('.$app->auth->email.')'],
			'password' => [$request->post('password'), 'required']
		]);
	} else {
		$v->validate([
			'first_name' => [$request->post('f_name'), 'max(50)'],
			'last_name' => [$request->post('l_name'), 'max(50)'],
			'email' => [$request->post('email'), 'required|email|uniqueEmaiupdateUser('.$app->auth->email.')'],
		]);
	}

	$v->addFieldMessages([
	    'first_name' => [
	        'max' => 'First name is too large',
	    ],
	    'last_name' => [
	        'max' => 'Last name is too large',
	    ]
	]);

	if ($v->passes()) {

		if ($app->auth->isLocalUser()) {

			if($app->hash->passwordCheck($request->post('password'), $app->auth->password)) {
				
				$app->auth->update([
					'f_name' => $request->post('first_name'),
					'l_name' => $request->post('last_name'),
					'email' => $request->post('email')
				]);

				echo json_encode([
					'status' => 200,
					'msg' => 'Profile update successful',
				]);

			}else {
				echo json_encode([
					'status' => 400,
					'msg' => 'Current password is incorrect',
				]);
			}
		} else {
			$app->auth->update([
				'f_name' => $request->post('first_name'),
				'l_name' => $request->post('last_name'),
				'email' => $request->post('email'),
			]);
			
			echo json_encode([
				'status' => 200,
				'msg' => 'Profile update successful',
			]);
		}
		
	}

})->name('account_update');


$app->post('/account/change-passsword', $authCheck(true), $localUser(), $activated(), function() use($app) {

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

		if($app->hash->passwordCheck($request->post('password'), $app->auth->password)) {

			$app->auth->update([
				'password' =>  $app->hash->passwordHash($request->post('password_new'))
			]);

			echo json_encode([
				'status' => 200,
				'msg' => 'Password is successful',
			]);

			$app->mail->send('email/auth/change.php', [], function($message) use ($app) {
				$message->to($app->auth->email);
				$message->subject('Password Changed Successfull');
			});

		} else {
			echo json_encode([
				'status' => 400,
				'msg' => 'Current password is incorrect',
			]);
		}
	}

})->name('account_password');


$app->post('/account/propic', $authCheck(true), $localUser(), function() use ($app) {

	$imgdata = json_decode($app->request->post('avatar_data'));
	$handle = new upload($_FILES['avatar_file']);

	if ($handle->uploaded) {

	  $path = APP_PATH.$app->config->get('app.avatar').$app->auth->id.'/';

	  $handle->file_new_name_body = uniqid(time()+3600);
	  $handle->allowed = array('image/*');
	  $handle->forbidden = array('application/*');
	  $handle->file_max_size = '2000000';
	  $handle->process($path);

	  if ($handle->processed) {

	  	$name = $app->hash->hash($app->randomlib->generateString(32));
	  	
	  	$file = $app->config->get('app.url').$app->config->get('app.avatar').$app->auth->id.'/'.$handle->file_dst_name_body.'.'.$handle->file_dst_name_ext;
	  	
	  	if ($app->auth->hasAvatar() && file_exists(APP_PATH.$app->auth->avatar)) {
	  		unlink(APP_PATH.$app->auth->avatar);
	  	}

	  	$background = Image::canvas(100, 100, '#ffffff');

	  	$image = Image::make($file)
	  		->crop(round($imgdata->width), round($imgdata->height), round($imgdata->x), round($imgdata->y))
	  		->resize(100, 100, function ($c) {
				    $c->aspectRatio();
				    $c->upsize();
			});

	  	$background->insert($image, 'center');	
	  	$background->save($path.$name.'.jpg');

	  	unlink($handle->file_dst_pathname);
	  	$handle->clean();
	  
	  
	  	$app->auth->update([
	  		'avatar' => $app->config->get('app.avatar').$app->auth->id.'/'.$name.'.jpg'
	  	]);

	  	echo json_encode(array(
            'state'  => 200,
            'message' => 'Uploaded Successful',
            'result' => $app->config->get('app.url').$app->config->get('app.avatar').$app->auth->id.'/'.$name.'.jpg'      
        ));

	  } else {
	  	echo json_encode(array(
			'state' => 200,
			'message' =>  $handle->error
		));
		$handle->clean();
	  }

	}

})->name('account_propic');

/*
user check email post method
*/
$app->post('/admin/users/checkemail',  $authCheck(true), function() use($app) {

	if ($app->request->isAjax()) {

		if ($app->auth->email == $app->request->post('email')) {
			$isAvailable = true;
		} else {
			if ($app->user->where('email', '=', $app->request->post('email'))->count()) {
	    		$isAvailable = false;
	    	} else {
	    		$isAvailable = true;
	    	}
	   	}

		echo json_encode(array(
			'valid' => $isAvailable,
		));
	}

})->name('auth_update_user_email_check');