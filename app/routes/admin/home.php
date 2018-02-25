<?php

use Lucids\Models\Deals\Source;
use Lucids\Models\Deals\Deal;
use Lucids\Models\Deals\Category;

$app->get('/admin/', $adminAuth(true), function() use ($app) {


	$app->render('admin/home.php', [
	  'deals' => Deal::get()->count(),
	  'sources' => Source::get()->count(),
	  'users' => $app->user->get()->count(),
	  'categories' => Category::get()->count()
	]);

})->name('admin_home');
