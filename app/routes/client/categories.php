<?php

use Lucids\Models\Deals\Category;
use Carbon\Carbon;


$app->get('/category/:slug/:id', function($slug, $id) use ($app) {

	$category = Category::find($id);

	if ($category && $category->slug == $slug) {

		$page = $app->request->get('page');
		$per_page = $app->config->get('perpage.deals');

		$page = (isset($page) && is_numeric($page) ) ? $page : 1;

		$count = $category->deals()
						  ->where(function($q){
							$q->where('expirey', '>', Carbon::now())->orWhere('expirey', '=', NULL);
						  })
						  ->get()
						  ->count();

		$start = ($page-1) * $per_page;

		$deals = $category->deals()
						  ->where(function($q){
							$q->where('expirey', '>', Carbon::now())->orWhere('expirey', '=', NULL);
						  })
						  ->limit($per_page)
						  ->offset($start)
						  ->orderBy('updated_at', 'DESC')
						  ->get();

		
		$app->render('client/categories/home.php',  [
			'category' => $category,
			'deals' => $deals,
			'page' => $page,
			'pages' => ceil($count / $per_page)
		]);

	} else {

		$app->notFound();
	}

})->name('category_single');