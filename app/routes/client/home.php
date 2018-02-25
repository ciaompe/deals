<?php
use Lucids\Models\Deals\Deal;
use ReCaptcha\ReCaptcha;

$app->get('/', function() use ($app) {

	$app->render('client/home.php', [

	  'featuredDeals' => Deal::where('featured', 1)
	  					->where(function ($q) {
	  						$q->expirey($q);	
	  					})
	  					->orderBy('id', 'DESC')
	  					->get(),

	  'latestDeals'  => Deal::limit(4)->expirey()->orderBy('updated_at', 'DESC')->get(),
	  'expireSoon' => Deal::limit(4)->expires()->get()
	]);

})->name('home');


$app->get('/contact', function() use ($app) {

	$app->render('client/contact.php');

})->name('contact');


$app->post('/contact', function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$v->validate([
		'name' => [$request->post('name'), 'required'],
		'email' => [$request->post('email'), 'required|email'],
		'subject' => [$request->post('subject'), 'required'],
		'message' => [$request->post('message'), 'required'],
	]);

	$recaptcha = new ReCaptcha($app->config->get('recaptcha.secret'));
	$resp = $recaptcha->verify($request->post('g-recaptcha-response'), $request->getIp());


	if ($v->passes() && $resp->isSuccess()) {
		
		$app->mail->send('email/client/contact.php', ['name' => $request->post('name'), 'email' => $request->post('email'), 'subject' => $request->post('subject'), 'message' => $request->post('message')], function($message) use ($app) {
			$message->to($app->config->get('app.contact.email'));
			$message->subject($app->config->get('app.sitename'));
		});
		$app->flash('success', 'Message sent successful');
		$app->response->redirect($app->urlFor('contact'));
	}

	$app->render('client/contact.php', [
		'request' => $request,
		'errors' => $v->errors(),
		'capcha' => $resp->getErrorCodes()
	]);

});