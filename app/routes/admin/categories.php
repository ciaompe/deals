<?php

use Intervention\Image\ImageManagerStatic as Image;
use Lucids\Models\Deals\Category;

use Lucids\Helpers\CategoryHelper;

/*
Category Manager get route
*/
$app->get('/admin/category/manager', $adminAuth(true), $admin(), function() use ($app) {

	unset($_SESSION['deals_category_logo']);

	$categories = Category::orderBy('depth', 'ASC')->get()->toArray();

	$app->render('/admin/categories/manager.php', [
		'categories' => CategoryHelper::buildTree($categories)
	]);

})->name('admin_category_manager');


/*
Create Category post
*/
$app->post('/admin/category/create', $adminAuth(true), $admin(), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$v->validate([
		'name' => [$request->post('name'), 'required|max(250)'],
		'description' => [$request->post('description'), 'required'],
	]);

	$image = (isset($_SESSION['deals_category_logo'])) ? $_SESSION['deals_category_logo'] : NULL;

	if ($v->passes()) {

		$category = Category::select('depth')->orderBy('id', 'desc')->first();

		Category::create([
			'name' => $request->post('name'),
			'dis' => $request->post('description'),
			'depth' => $category->depth + 1,
			'image' => ( ($image != NULL) || ($image != "") ) ? $app->config->get('app.categories').$image : NULL
		]);

		if ($image != NULL || $image != "") {
			if (file_exists(APP_PATH.$app->config->get('app.tmp').$image)) {
				rename(APP_PATH.$app->config->get('app.tmp').$image, APP_PATH.$app->config->get('app.categories').$image);
				unset($_SESSION['deals_category_logo']);
			}
		}

		echo json_encode(array(
            'status'  => 200,
            'message' => 'Uploaded Successful' 
        ));
	}

})->name('admin_ctaegory_create');

/*
Update Category post
*/
$app->get('/admin/category/update/:id', $adminAuth(true), $admin(), function($id) use ($app) {

	$category = Category::find($id);

	if ($category) {
		
		$app->render('/admin/categories/update.php', [
			'category' => $category
		]);


	} else {
		$app->notFound();
	}

})->name('admin_category_update');


/*
Update Category post
*/
$app->post('/admin/category/update/', $adminAuth(true), $admin(), function() use ($app) {

	$request = $app->request;

	$category = Category::find($request->post('id'));

	if ($category) {

		$v = $app->validator;

		$v->validate([
			'name' => [$request->post('name'), 'required|max(250)'],
			'description' => [$request->post('description'), 'required'],
		]);

		if ($v->passes()) {
			
			$category->update([
				'name' => $request->post('name'),
				'dis' => $request->post('description')
			]);

			echo json_encode([
				'status' => 200
			]);	
		}

	}

})->name('admin_category_update_post');

/*
Update Category list
*/
$app->post('/admin/category/update/list/', $adminAuth(true), $admin(), function() use ($app) {
	$data  	= 	json_decode($app->request->post('data'),true, 64);
	$data 	= 	CategoryHelper::setArray($data);

	foreach ($data as $key => $value) {

		if (is_array($value)) {
			Category::where('id', $value['id'])->update([
				'depth' => $key,
				'parent' => $value['parent']
			]);
		}

	}
})->name('admin_ctaegory_update_list');

/*
Delete Category
*/
$app->get('/admin/category/delete/:id', $adminAuth(true), $admin(), function($id) use ($app) {

	$category = Category::find($id);

	if ($category) {

		$subCats = Category::where('parent', $id);

		if ($subCats->count()) {

			foreach ($subCats->get() as $cat) {

				$cat->deals()->detach();
				$cat->delete();

				if ($cat->hasImage()) {
					if (file_exists(APP_PATH.$cat->image)) {
						unlink(APP_PATH.$cat->image);
					}
				}

			}
		}

		$category->deals()->detach();
		$category->delete();

		if ($category->hasImage()) {
			if (file_exists(APP_PATH.$category->image)) {
				unlink(APP_PATH.$category->image);
			}
		}

		$app->flash('success', 'Category deleted successful');
		$app->response->redirect($app->urlFor('admin_category_manager'));
		
	} else {
		$app->notFound();
	}

})->name('admin_category_delete');

/*
Category Image upload post
*/

$app->post('/admin/category/image/upload', $adminAuth(true), $admin(), function() use ($app) {

	$image = (isset($_SESSION['deals_category_logo'])) ? $_SESSION['deals_category_logo'] : NULL;

	if ($image != NULL || $image != "") {
		if (file_exists(APP_PATH.$app->config->get('app.tmp').$image)) {
			unlink(APP_PATH.$app->config->get('app.tmp').$image);
		}
	}

	$imgdata = json_decode($app->request->post('avatar_data'));
	$handle = new upload($_FILES['avatar_file']);

	if ($handle->uploaded) {

	  $path = APP_PATH.$app->config->get('app.tmp');

	  $handle->file_new_name_body = uniqid(time()+3600);
	  $handle->allowed = array('image/*');
	  $handle->forbidden = array('application/*');
	  $handle->file_max_size = '2000000';
	  $handle->process($path);

	  if ($handle->processed) {

	  	$name = $app->hash->hash($app->randomlib->generateString(32));
	  	
	  	$file = $app->config->get('app.url').$app->config->get('app.tmp').$handle->file_dst_name_body.'.'.$handle->file_dst_name_ext;
	  	
	  	$background = Image::canvas($app->config->get('images.category.width'), $app->config->get('images.category.height'), '#ffffff');

	  	$image = Image::make($file)
	  			 ->crop(round($imgdata->width), round($imgdata->height), round($imgdata->x), round($imgdata->y))
	  			 ->resize($app->config->get('images.category.width'), $app->config->get('images.category.height'), function ($c) {
				    $c->aspectRatio();
				    $c->upsize();
				 });

	  	$background->insert($image, 'center');
	  	$background->save($path.$name.'.jpg');

	  	unlink($handle->file_dst_pathname);
	  	$handle->clean();

	  	$_SESSION['deals_category_logo'] = $name.'.jpg';
	 
	  	echo json_encode(array(
            'state'  => 200,
            'message' => 'Uploaded Successful',
            'result' => $app->config->get('app.url').$app->config->get('app.tmp').$name.'.jpg'      
        ));

	  } else {
	  	echo json_encode(array(
			'state' => 200,
			'message' =>  $handle->error
		));
		$handle->clean();
	  }
	}

})->name('admin_deals_upload_ctaegory_image');

/*
Category Image update post
*/

$app->post('/admin/category/image/update/:id', $adminAuth(true), $admin(), function($id) use ($app) {

	$category = Category::find($id);

	if ($category) {

		$imgdata = json_decode($app->request->post('avatar_data'));
		$handle = new upload($_FILES['avatar_file']);

		if ($handle->uploaded) {

		  $path = APP_PATH.$app->config->get('app.categories');

		  $handle->file_new_name_body = uniqid(time()+3600);
		  $handle->allowed = array('image/*');
		  $handle->forbidden = array('application/*');
		  $handle->file_max_size = '2000000';
		  $handle->process($path);

		  if ($handle->processed) {

		  	$name = $app->hash->hash($app->randomlib->generateString(32));
		  	
		  	$file = $app->config->get('app.url').$app->config->get('app.categories').$handle->file_dst_name_body.'.'.$handle->file_dst_name_ext;
		  	
		  	$background = Image::canvas($app->config->get('images.category.width'), $app->config->get('images.category.height'), '#ffffff');

		  	$image = Image::make($file)
		  			->crop(round($imgdata->width), round($imgdata->height), round($imgdata->x), round($imgdata->y))
		  			->resize($app->config->get('images.category.width'), $app->config->get('images.category.height'), function ($c) {
					    $c->aspectRatio();
					    $c->upsize();
					});

		  	$background->insert($image, 'center');
		  	$background->save($path.$name.'.jpg');

		  	unlink($handle->file_dst_pathname);
		  	$handle->clean();

		  	if ($category->hasImage()) {
				if (file_exists(APP_PATH.$category->image)) {
					unlink(APP_PATH.$category->image);
				}
			}

		  	$category->update([
		  		'image' => $app->config->get('app.categories').$name.'.jpg'
		  	]);
		 
		  	echo json_encode(array(
	            'state'  => 200,
	            'message' => 'Uploaded Successful',
	            'result' => $app->config->get('app.url').$app->config->get('app.categories').$name.'.jpg'      
	        ));

		  } else {
		  	echo json_encode(array(
				'state' => 200,
				'message' =>  $handle->error
			));
			$handle->clean();
		  }

		}

	}else {
		$app->notFound();
	}

})->name('admin_deals_update_category_image');




