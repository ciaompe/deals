<?php

use LiveControl\EloquentDataTable\DataTable;

/*
Admin Create user, Staff or admin GET route
*/
$app->get('/admin/users/create', $adminAuth(true), $admin(), function() use($app) {
	$app->render('/admin/users/create.php');
})->name('admin_create_user');

/*
Admin create user POST route
*/
$app->post('/admin/users/create', $adminAuth(true), $admin(), function() use($app) {

	$request = $app->request;
	$v = $app->validator;

	$v->validate([
		'first_name' => [$request->post('first_name'), 'required|max(50)'],
		'last_name' => [$request->post('last_name'), 'required|max(50)'],
		'email' => [$request->post('email'), 'required|email|uniqueEmailUser'],
		'password' => [$request->post('password'), 'required'],
		'role' => [$request->post('role'), 'required'],
	]);

	$v->addFieldMessages([
	    'first_name' => [
	    	'required' => 'first name is required',
	        'max' => 'first name is too large',
	    ],
	    'last_name' => [
	    	'required' => 'last name is required',
	        'max' => 'last name is too large',
	    ],
	    'role' => [
	    	'required' => 'user role is required'
	    ]
	]);

	if ($v->passes()) {

		$user = $app->user->create([
			'email' => $request->post('email'),
			'f_name' => $request->post('first_name'),
			'l_name' => $request->post('last_name'),
			'password' => $app->hash->passwordHash($request->post('password')),
			'activate' => 1,
		]);

		if ($request->post('role') == "admin") {
			$user->permissions()->create([
					'is_admin' => true,
					'is_staff' => false,
					'is_ban' => false,
			]);
		}
		if ($request->post('role') == "staff") {
			$user->permissions()->create([
					'is_admin' => false,
					'is_staff' => true,
					'is_ban' => false,
			]);
		}

		$app->flash('success', 'User Created Successfull');
		$app->response->redirect($app->urlFor('admin_user_manage'));
		
	}

	$app->render('/admin/users/create.php', [
		'errors' => $v->errors(),
		'request' => $request
	]);


});

/*
Admin user create check email post method
*/
$app->post('/admin/users/check/email', $adminAuth(true), $admin(), function() use($app) {

	if ($app->user->where('email', $app->request->post('email'))->count()) {

		$isAvailable = false;

	} else {
		$isAvailable = true;
	}

	echo json_encode(array(
		'valid' => $isAvailable,
	));
	
})->name('admin_user_email_check');

/*
Admin manage all users get method
*/
$app->get('/admin/users/manage', $adminAuth(true), $admin(), function() use($app) {

	$app->render('/admin/users/manage.php');

})->name('admin_user_manage');


$app->post('/admin/users/manage', $adminAuth(true), $admin(), function() use($app) {

	$type = $app->request->post('userType');

	if ($type != "" || $type != null) {

		switch ($type) {
			case 'admin':
				dataTable($app, 'is_admin');
				break;
			case 'staff':
				dataTable($app, 'is_staff');
				break;
			case 'ban':
				dataTable($app, 'is_ban');
				break;
		}

	} else {

		$users = $app->user;

		$dataTable = new DataTable($users->orderBy('id', 'DESC'), ['id', 'f_name', 'l_name', 'email', 'avatar','activate', 'identifier', 'created_at']);

	    $dataTable->setFormatRowFunction(function ($users) use ($app){
	      return [
	      	$users->getAvatar($app->config->get('app.url'), $app->config->get('app.images')),
	        $users->getName(),
	        $users->email,
	        $users->created_at->diffForHumans(),
	        $users->userType(),
	        $users->id,
	        $users->isBan(),
	        $users->activate
	      ];
	    });

	    echo json_encode($dataTable->make());
	}

});

function dataTable($app, $type) {

	$users = $app->user;

	$dataTable = new DataTable($users->join('users_permissions', 'users.id', '=', 'users_permissions.user_id')->where('users_permissions.'.$type, 1), ['users.id', 'f_name', 'l_name', 'email', 'avatar','activate', 'identifier', 'created_at', 'is_admin', 'is_staff', 'is_ban']);

	    $dataTable->setFormatRowFunction(function ($users) use ($app){

	   	 $title = '';

	     if ($users->is_admin) {
	     	$title = "Admin";
	     }
	     if ($users->is_staff) {
	     	$title = "Staff";
	     }

	      return [
	      	$users->getAvatar($app->config->get('app.url'), $app->config->get('app.images')),
	        $users->getName(),
	        $users->email,
	        $users->created_at->diffForHumans(),
	       	$title,
	        $users->usersId,
	        $users->is_ban,
	        $users->activate
	      ];
	    });

	echo json_encode($dataTable->make());
}


/*
Admin user update check email post method
*/
$app->post('/admin/users/update/checkemail', $adminAuth(true), $admin(), function() use($app) {

	if ($app->request->isAjax()) {

		$user = $app->user->find($app->request->post('id'));

		if ($user->email == $app->request->post('email')) {

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

})->name('admin_user_update_email_check');


/*
Admin User update
*/
$app->get('/admin/users/update/:id', $adminAuth(true), $admin(), function ($id) use ($app) {

	$user = $app->user->find($id);

	if ($user) {
	  
	  if($user->id === $_SESSION[$app->config->get('auth.admin')]) {
	    $app->flash('error', 'You have not permission to edit your own account, please use an account section on your sidebar menu');
		  $app->response->redirect($app->urlFor('admin_user_manage'));
	  }

		$app->render('/admin/users/edit.php', ['user' => $user]);

	} else {
		$app->notFound();
	}
	

})->name('admin_users_update');

/*
Admin User update post
*/
$app->post('/admin/users/update/:id', $adminAuth(true), $admin(), function ($id) use ($app) {

	$user = $app->user->find($id);

	if ($user) {
	  
	 if($user->id === $_SESSION[$app->config->get('auth.admin')]) {
	   $app->flash('error', 'You have not permission to edit your own account, please use an account section on your sidebar menu');
		  $app->response->redirect($app->urlFor('admin_user_manage'));
	 }
	
		$request = $app->request;
		$v = $app->validator;

		if ($user->isLocalUser()) {
			$v->validate([
				'first_name' => [$request->post('first_name'), 'max(50)'],
				'last_name' => [$request->post('last_name'), 'max(50)'],
				'password' => [$request->post('password'), 'min(6)'],
				'email' => [$request->post('email'), 'required|email|uniqueEmaiupdateUser('.$user->email.')'],
			]);
		} else {
			$v->validate([
				'first_name' => [$request->post('first_name'), 'max(50)'],
				'last_name' => [$request->post('last_name'), 'max(50)'],
				'email' => [$request->post('email'), 'email|uniqueEmaiupdateUser('.$user->email.')'],
			]);
		}

		$v->addFieldMessages([
		    'first_name' => [
		        'max' => 'first name is too large',
		    ],
		    'last_name' => [
		        'max' => 'last name is too large',
		    ]
		]);

		if ($v->passes()) {

			$data = array(
				'f_name' => $request->post('first_name'),
				'l_name' => $request->post('last_name'),
				'email' => $request->post('email')
			);

			if ($user->isLocalUser() && ($request->post('password') != "" || $request->post('password') != null )) {
				$data['password'] = $app->hash->passwordHash($request->post('password'));
			}

			$user->update($data);

			$permission = array(
				'is_admin' => false,
				'is_staff' => false
			);

			if ($request->post('role') == "admin") {
				$permission['is_admin'] = true;
			}
			
			if ($request->post('role') == "staff") {
				$permission['is_staff'] = true;
			}

			$user->permissions()->update($permission);

			$app->flash('success', 'User updated Successfull');
			$app->response->redirect($app->urlFor('admin_users_update', ['id' => $user->id]));

		}

		$app->render('/admin/users/edit.php', [
			'errors' => $v->errors(),
			'request' => $request,
			'user' => $user
		]);


	} else {
		$app->notFound();
	}

})->name('admin_users_update_post');

/*
Admin User ban
*/
$app->get('/admin/users/ban/:id', $adminAuth(true), $admin(), function ($id) use ($app) {
	
	$user = $app->user->find($id);

	if ($user) {

		$user->permissions()->update(array(
			'is_ban' => true
		));
		$user->comments()->update([
			'status' => 0
		]);

		$app->flash('success', 'User Banned Successfull');
		$app->response->redirect($app->urlFor('admin_users_update', ['id' => $user->id]));

	} else {

		$app->notFound();
	}

})->name('admin_users_ban');

/*
Admin User unban
*/
$app->get('/admin/users/unban/:id', $adminAuth(true), $admin(), function ($id) use ($app) {

	$user = $app->user->find($id);

	if ($user) {

		$user->permissions()->update(array(
			'is_ban' => false
		));
		$user->comments()->update([
			'status' => 1
		]);

		$app->flash('success', 'User Unbanned Successfull');
		$app->response->redirect($app->urlFor('admin_users_update', ['id' => $user->id]));

	} else {

		$app->notFound();
	}

})->name('admin_users_unban');

/*
Admin User activate
*/
$app->get('/admin/users/activate/:id', $adminAuth(true), $admin(), function ($id) use ($app) {

	$user = $app->user->find($id);

	if ($user) {

		$user->update(array(
			'activate' => true,
			'activate_hash' => NULL
		));

		$app->flash('success', 'User Activated Successfull');
		$app->response->redirect($app->urlFor('admin_users_update', ['id' => $user->id]));

	} else {

		$app->notFound();
	}

})->name('admin_users_activate');

/*
Admin User delete
*/
$app->get('/admin/users/delete/:id', $adminAuth(true), $admin(), function ($id) use ($app) {
	
	$user = $app->user->find($id);

	if ($user) {

		if ($user->isLocalUser()) {

			if($user->hasAvatar()) {

				if (file_exists(APP_PATH.$user->avatar)) {
					unlink(APP_PATH.$user->avatar);
				}

				if (is_dir(APP_PATH.$app->config->get('app.avatar').$user->id) ) {
					rmdir(APP_PATH.$app->config->get('app.avatar').$user->id);
				}

			}
		}

		$user->permissions()->delete();
		$user->comments()->delete();
		$user->rates()->delete();
		$user->deals()->delete();

		$user->delete();

		$app->flash('success', 'User Deleted Successfull');
		$app->response->redirect($app->urlFor('admin_user_manage'));

	}else {
		$app->notFound();
	}

})->name('admin_users_delete');

/*
Admin User reset propic
*/
$app->get('/admin/users/reset/:id', $adminAuth(true), $admin(), function ($id) use ($app) {
	
	$user = $app->user->find($id);

	if ($user) {

		if ($user->isLocalUser()) {

			if($user->hasAvatar()) {

				if (file_exists(APP_PATH.$user->avatar)) {
					unlink(APP_PATH.$user->avatar);
				}

				if (is_dir(APP_PATH.$app->config->get('app.avatar').$user->id) ) {
					rmdir(APP_PATH.$app->config->get('app.avatar').$user->id);
				}

			}
		}

		$user->update(array(
			'avatar' => NULL
		));

		$app->flash('success', 'User Avatar Resetd Successfull');
		$app->response->redirect($app->urlFor('admin_users_update', ['id' => $user->id]));

	} else {

		$app->notFound();
	}

})->name('admin_users_reset_propic');

