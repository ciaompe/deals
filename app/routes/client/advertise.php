<?php
use Lucids\Models\Advertise\Adunit;
/*
Advertise url redirect
*/
$app->get('/advertise/redirect/:id', function($id) use ($app) {

	$adunit = Adunit::find($id);

	$token = hash('sha256', $app->request->getIp().'adoHukapn$^&*ponnaya');

	if($adunit && $adunit->type == "image" && ($token == $app->request->get('token')) ) {
		
		$app->redirect($adunit->url, 301);
		
	} else {
		$app->redirect($app->urlFor('home'), 301);
	}

})->name('advertise');