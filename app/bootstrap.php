<?php
/*
Import Slim Libs
*/
use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

/*
Import configuration lib
*/
use Noodlehaus\Config;

/*
Import randomlib factory
*/
use RandomLib\Factory as RandomLib;

/*
Import Lucids Models
*/
use Lucids\Models\Auth\User;

/*
Import Lucids Helpers
*/
use Lucids\Helpers\Hash;
use Lucids\Helpers\Validator;

/*
Import Lucids Mail Helper class powerd by PHP Mailer
*/
use Lucids\Helpers\Mailer\Mailer;

/*
Import Lucids application Middlewares
*/
use Lucids\Middleware\BeforeMiddleware;
use Lucids\Middleware\CsrfMiddleware;
use Lucids\Middleware\DataMiddleware;

use Lucids\Lib\Twig\Extenstions\ShowAd;
use Lucids\Lib\Twig\Extenstions\FileExists;

/*
App Sesssions
*/
session_cache_limiter(false);
session_start();

/*
Display errors turn on
*/
ini_set('display_errors', 'On');

/**
Constant for direct app path
*/
defined('APP_PATH') || define('APP_PATH', dirname(__DIR__));

/**
Constant for direct app HOST
*/
defined('APP_HOST') || define('APP_HOST', 'http://'.$_SERVER['HTTP_HOST']);

/*
Load all vendors
*/
require APP_PATH.'/vendor/autoload.php';

/*
Slim Application Instance and create twig instance for the slim view
*/
$app = new Slim([
	'debug' => true,
	'view' => new Twig(),
	'templates.path' => APP_PATH.'/app/views'
]);

/*
Set app configurations to the slim app using hassankhan configuration lib
*/
$app->config = Config::load(APP_PATH.'/app/config/config.php');

/*
Set Default timezone to php
 */
date_default_timezone_set($app->config->get('app.timezone'));

/*
Load Database Capsule
*/
require APP_PATH.'/app/config/database.php';

/*
Add User Obj to slim container, after adding to the slim container you can use User(OBJ) $app->user
*/
$app->container->set('user', function() use ($app){
	return new User;
});

/*
Set authetication variable to false
*/
$app->auth = false;

/*
Set admin variable to false
*/
$app->admin = false;

/*
Add Lucids Middlewares to the silm application
*/
$app->add(new BeforeMiddleware);
$app->add(new CsrfMiddleware);
$app->add(new DataMiddleware);


/*
Add hash Helper to the slim app
*/
$app->container->singleton('hash', function() use ($app) {
	return new Hash($app->config);
});

/*
Add Randomlib lib to slim container
*/
$app->container->singleton('randomlib', function() use ($app) {
	$factory = new RandomLib;
	return $factory->getMediumStrengthGenerator();
});

/*
Add validation Helper to the slim app
*/
$app->container->singleton('validator', function() use ($app) {
	return new Validator;
});

/*
Add php Mailer obj to the slim container
*/
$app->container->singleton('mail', function() use($app) {

	$mailer = new PHPMailer;

	$mailer->isSMTP();

	$mailer->Host = $app->config->get('mail.host');
	$mailer->SMTPAuth = $app->config->get('mail.smtp_auth');
	$mailer->Username =  $app->config->get('mail.username');
	$mailer->Password =  $app->config->get('mail.password');
	$mailer->SMTPSecure =  $app->config->get('mail.smtp_secure');
	$mailer->Port =  $app->config->get('mail.port');

	$mailer->setFrom($app->config->get('mail.from'),  $app->config->get('mail.name'));
	
	$mailer->isHTML($app->config->get('mail.html')); 

	return new Mailer($app->view, $mailer);
});

/*
Configurataion Slim view with twig
*/
$view = $app->view();
$view->parserOptions = [
	'debug' => $app->config->get('twig.debug')
];
$view->parserExtensions = [
	new TwigExtension,
	new FileExists,
	new ShowAd
];


/*
Hybrid Auth
*/
$app->container->singleton('hybridAuth', function() use($app) {
	return new Hybrid_Auth($app->config->get('hybrid_auth'));
});

/*
Load route middelwares
*/
require APP_PATH.'/app/middleware.php';

/*
Load all routes 
*/
require APP_PATH.'/app/routes.php';