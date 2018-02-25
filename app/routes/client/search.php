<?php

use Lucids\Models\Deals\Deal;
use Carbon\Carbon;


$app->get('/search/', function() use ($app) {

	$deal = Deal::query();

	$source = is_numeric($app->request->get('source')) ? $app->request->get('source') : NULL;
	$category = is_numeric($app->request->get('category')) ? $app->request->get('category') : NULL;

	if ($app->request->get('q')) {
		$deal->where('title', 'LIKE', '%'.$app->request->get('q').'%');
	}

	if ($source !== NULL) {
		$deal->where('source_id', $source);
	}

	if ($category != NULL) {

		$deal->whereHas('categories', function($q) use ($app, $category)
		{
		    $q->where('category_id', $category);

		});
	}

	if ($app->request->get('type')) {
		$deal->where('type', $app->request->get('type'));
	}

	$deal->where(function ($q) {
      	$q->expirey($q);
    });

	$page = $app->request->get('page');
	$per_page = $app->config->get('perpage.deals');
	$page = (isset($page) && is_numeric($page) ) ? $page : 1;

	$count = $deal->get()->count();
	$start = ($page-1) * $per_page;

	$deals = $deal->limit($per_page)->offset($start)->orderBy('updated_at', 'DESC')->get();


	$app->render('client/search/search.php', [
		'deals' => $deals,
		'page' => $page,
		'pages' => ceil($count / $per_page),
		'request' => $app->request
	]);								


})->name('filter');