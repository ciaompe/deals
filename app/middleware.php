<?php

$authCheck = function($required) use ($app) {

	return function() use($required, $app) {

		if(!$app->auth && $required) {
			$_SESSION['urlRedirect'] = $app->request()->getPath();
			$app->flash('error', 'Login required');
			$app->redirect($app->urlFor('auth_login'));
		}
		if ($app->auth && !$required){
			$app->redirect($app->urlFor('home'));
		}
	};
};

$localUser = function() use ($app) {
	return function() use($app) {
		if ( !$app->auth->isLocalUser()) {
			$app->redirect($app->urlFor('home'));
		}
	};
};

$activated = function() use ($app) {
    return function() use($app) {
        if(!$app->auth->isActivated()){
            $app->flash('error', 'Please Activate your account!');
			$app->redirect($app->urlFor('home'));
        }
    };  
    
};

/*
Admin middlewares
*/
$adminAuth = function($required) use ($app) {

	return function() use($required, $app) {

		if( (!$app->admin && $required)) {
			$app->flash('error', 'Login required');
			$app->redirect($app->urlFor('admin_login'));
		}
		if($app->admin && !$required) {
			$app->redirect($app->urlFor('admin_home'));
		}
	};
};

$admin = function() use ($app) {
	return function() use($app) {
		if (!$app->admin->isAdmin()) {
			$app->flash('error', 'User has not permissions to access that page');
			$app->redirect($app->urlFor('admin_home'));
		}
	};
};
