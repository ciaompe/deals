<?php

use Intervention\Image\ImageManagerStatic as Image;

use Lucids\Models\Deals\Source;
use Lucids\Models\Deals\Category;
use Lucids\Models\Deals\Deal;
use Lucids\Models\Deals\DealImage;
use Lucids\Models\Deals\DealComment;

use Carbon\Carbon;

use LiveControl\EloquentDataTable\DataTable;

use Lucids\Helpers\CategoryHelper;

/*
Deals source create get route
*/
$app->get('/admin/deals/source/create', $adminAuth(true), function() use ($app) {

	unset($_SESSION['deals_source_logo']);
	$app->render('/admin/deals/createSource.php');

})->name('admin_deals_source_create');

/*
Deals source create post route
*/
$app->post('/admin/deals/source/create', $adminAuth(true), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$sourceName = strtolower( preg_replace('/\s/', '', $request->post('name')) );

	$v->validate([
		'name' => [$sourceName, 'required|max(20)|uniqueSourceName'],
		'url' => [$request->post('url'), 'required|url'],
	]);

	$image = (isset($_SESSION['deals_source_logo'])) ? $_SESSION['deals_source_logo'] : NULL;

	if ($v->passes()) {

		Source::create([
			'name' => $sourceName,
			'url' => $request->post('url'),
			'logo' => ( ($image != NULL) || ($image != "") ) ? $app->config->get('app.deals').'source/'.$image : NULL
		]);

		if ($image != NULL || $image != "") {
			if (file_exists(APP_PATH.$app->config->get('app.tmp').$image)) {
				rename(APP_PATH.$app->config->get('app.tmp').$image, APP_PATH.$app->config->get('app.deals').'source/'.$image);
				unset($_SESSION['deals_source_logo']);
			}
		}
		
		$app->flash('success', 'Source created successful');
		$app->response->redirect($app->urlFor('admin_deals_source_manage'));

	}

	$app->render('/admin/deals/createSource.php', [
		'errors' => $v->errors(),
		'source_logo' =>  ( ($image != NULL) || ($image != "") ) ? $app->config->get('app.url').$app->config->get('app.tmp').$image : NULL,
		'request' => $request
	]);
});

/*
Deals source image upload post route
*/
$app->post('/admin/deals/source/upload/source-logo', $adminAuth(true), function() use ($app) {

	$image = (isset($_SESSION['deals_source_logo'])) ? $_SESSION['deals_source_logo'] : NULL;
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
	  	
	  	$background = Image::canvas($app->config->get('images.source.width'), $app->config->get('images.source.height'), '#ffffff');

	  	$image = Image::make($file)
	  			 ->crop(round($imgdata->width), round($imgdata->height), round($imgdata->x), round($imgdata->y))
	  			 ->resize($app->config->get('images.source.width'), $app->config->get('images.source.height'), function ($c) {
				    $c->aspectRatio();
				    $c->upsize();
				 });

	  	$background->insert($image, 'center');
	  	$background->save($path.$name.'.jpg');

	  	unlink($handle->file_dst_pathname);
	  	$handle->clean();

	  	$_SESSION['deals_source_logo'] = $name.'.jpg';
	 
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

})->name('admin_deals_upload_source_logo');

/*
Deals source manage get route
*/
$app->get('/admin/deals/source/manage', $adminAuth(true), function() use ($app) {

	$app->render('/admin/deals/manageSource.php');

})->name('admin_deals_source_manage');

/*
Deals source manage post route
*/
$app->post('/admin/deals/source/manage', $adminAuth(true), function() use ($app) {

	$sources = new Source;

	$dataTable = new DataTable($sources->orderBy('id', 'DESC'), ['id', 'name', 'url', 'logo']);

    $dataTable->setFormatRowFunction(function ($sources) use ($app){
      return [
      	$sources->getLogo($app->config->get('app.url'), $app->config->get('app.images')),
        $sources->name,
        $sources->url,
        $sources->id
      ];
    });

	echo json_encode($dataTable->make());
});

/*
Deals source update get
 */

$app->get('/admin/deals/source/update/:id',  $adminAuth(true), function($id) use ($app) {
	
	unset($_SESSION['deals_source_logo']);

	$source = Source::find($id);

	if ($source) {
		$app->render('/admin/deals/updateSource.php', ['source' => $source]);
	} else {
		$app->notFound();
	}

})->name('admin_deals_source_update');

/*
Deals source update post
 */

$app->post('/admin/deals/source/update/:id',  $adminAuth(true), function($id) use ($app) {

	$source = Source::find($id);

	if ($source) {

		$request = $app->request;
		$v = $app->validator;

		$sourceName = strtolower( preg_replace('/\s/', '', $request->post('name')) );

		$v->validate([
			'name' => [$sourceName, 'required|uniqueSourceName('.$source->name.')'],
			'url' => [$request->post('url'), 'required|url'],
		]);

		$image = (isset($_SESSION['deals_source_logo'])) ? $_SESSION['deals_source_logo'] : NULL;

		if ($v->passes()) {

			$source->update([
				'name' => $sourceName,
				'url' => $request->post('url')
			]);

			if ($image != NULL || $image != "") {

				if ($source->logo != NULL || $source->logo != "") {
					if (file_exists(APP_PATH.$source->logo)) {
						unlink(APP_PATH.$source->logo);
					}
				}

				if (file_exists(APP_PATH.$app->config->get('app.tmp').$image)) {
					rename(APP_PATH.$app->config->get('app.tmp').$image, APP_PATH.$app->config->get('app.deals').'source/'.$image);
					unset($_SESSION['deals_source_logo']);
				}

				$source->update([
					'logo' => $app->config->get('app.deals').'source/'.$image
				]);

			}

			$app->flash('success', 'Source updated successful');
			$app->response->redirect($app->urlFor('admin_deals_source_update', ['id' => $source->id]));

		}

		$app->render('/admin/deals/updateSource.php', [
			'errors' => $v->errors(),
			'source_logo' =>  ( ($image != NULL) || ($image != "") ) ? $app->config->get('app.url').$app->config->get('app.tmp').$image : NULL,
			'request' => $request,
			'source' => $source
		]);

	} else {
		$app->notFound();
	}

});


/*
Deals source delete
*/
$app->get('/admin/deals/source/delete/:id', $adminAuth(true), $admin(), function($id) use ($app) {

	$source = Source::find($id);

	if ($source) {

		$deals = $source->deals;

		if ($deals->count()) {

			foreach ($deals as $deal) {

				$deal->categories()->detach();

				if ($deal->images->count()) {

					if (is_dir(APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id))
				    {
				        foreach ($deal->images as $image)
				        {
				        	if (file_exists(APP_PATH.$image->image.'.jpg')) {
				            	unlink(APP_PATH.$image->image.'.jpg');
				            	unlink(APP_PATH.$image->image.'_thumbnail.jpg');
				        	}
				        }
				        rmdir(APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id);
				    }

				    $deal->images()->delete();
				}

				$deal->rates()->delete();
				$deal->comments()->delete();
				$deal->users()->delete();
				$deal->delete();
			}

		}

		$source->delete();

		$app->flash('success', 'Source Deleted successful');
		$app->response->redirect($app->urlFor('admin_deals_source_manage'));

	} else {
		$app->notFound();
	}

})->name('admin_deals_source_delete');

/*
Deals create get
*/
$app->get('/admin/deals/create',  $adminAuth(true), function() use ($app) {

	$sources = Source::all();
	$categories = Category::orderBy('depth', 'ASC')->get()->toArray();

	$app->render('/admin/deals/createDeal.php', [
		'sources' => $sources,
		'categories' => CategoryHelper::buildTree($categories)
	]);

})->name('admin_deals_create');


/*
Deals create post
*/
$app->post('/admin/deals/create',  $adminAuth(true), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$sources = Source::all();
	$category = Category::all();

	if (CategoryHelper::validateCategory($request->post('category')) != 1) {
		throw new \Exception("Somting went wrong, try again");
	}

	if ($request->post('type') == "count") {
		$v->validate([
			'source' => [$request->post('source'), 'required|source'],
			'category' => [$request->post('category'), 'required|array'],
			'title' => [$request->post('title'), 'required|max(250)|min(20)'],
			'description' => [$request->post('description'), 'required'],
			'price' => [$request->post('price'), 'required|number'],
			'discount' => [$request->post('discount'), 'number'],
			'url' => [$request->post('url'), 'required|url'],
			'type' => [$request->post('type'), 'required|dealType'],
			'expirey' => [$request->post('expirey'), 'required'],
		]);
	} else {
		$v->validate([
			'source' => [$request->post('source'), 'required|source'],
			'category' => [$request->post('category'), 'required|array'],
			'title' => [$request->post('title'), 'required|max(250)|min(20)'],
			'description' => [$request->post('description'), 'required'],
			'price' => [$request->post('price'), 'required|number'],
			'discount' => [$request->post('discount'), 'number'],
			'url' => [$request->post('url'), 'required|url'],
			'type' => [$request->post('type'), 'required|dealType'],
		]);
	}

	if ($v->passes()) {

		$deal = Deal::create([
			'source_id' => $request->post('source'),
			'title' => $request->post('title'),
			'description' => base64_encode($request->post('description')),
			'price' => $request->post('price'),
			'discount' => $request->post('discount'),
			'url' => $request->post('url'),
			'type' => $request->post('type'),
			'expirey' => ($request->post('type') == "count") ? date("Y-m-d H:i:s", strtotime($request->post('expirey'))) : NULL,
			'featured' =>  ($request->post('featured') == "on") ? 1 : 0
		]);

		$deal->categories()->attach($request->post('category'));

		if (!empty($request->post('deal_images'))) {

			$i=0;

			mkdir(APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id, 0777, true);
			
			foreach ($request->post('deal_images') as $value) {

				if($i==5) break;
				
				if (file_exists(APP_PATH.$app->config->get('app.tmp').$value.'.jpg')) {

					$deal->images()->create([
						'image' => $app->config->get('app.deals').'deal/'.$deal->id.'/'.$value
					]);

					rename(APP_PATH.$app->config->get('app.tmp').$value.'.jpg', APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id.'/'.$value.'.jpg');
					rename(APP_PATH.$app->config->get('app.tmp').$value.'_medium.jpg', APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id.'/'.$value.'_medium.jpg');
					rename(APP_PATH.$app->config->get('app.tmp').$value.'_thumbnail.jpg', APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id.'/'.$value.'_thumbnail.jpg');
				}

				$i++;
			}
		}

		$app->flash('success', 'Deal Created successful');
		$app->response->redirect($app->urlFor('admin_deals_manage'));
		
	}

	$app->render('/admin/deals/createDeal.php', [
		'errors' => $v->errors(),
		'request' => $request,
		'sources' => $sources,
		'categories' => $category
	]);

});

/*
Deals upload image post
*/
$app->post('/admin/deals/upload/image',  $adminAuth(true), function() use ($app) {

	$handle = new upload($_FILES['file']);

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
			
			$background = Image::canvas($app->config->get('images.deal.image.width'), $app->config->get('images.deal.image.height'), '#ffffff');
	  		$background_medium = Image::canvas($app->config->get('images.deal.medium.width'), $app->config->get('images.deal.medium.height'), '#ffffff');
			$background_thumb = Image::canvas($app->config->get('images.deal.thumbnail.width'), $app->config->get('images.deal.thumbnail.height'), '#ffffff');

			
			$img = Image::make($file);
			$img2 = Image::make($file);
			$img3 = Image::make($file);

			$img->resize(null, $app->config->get('images.deal.thumbnail.height'), function ($c) {
            	$c->aspectRatio();
            });

            $background_thumb->insert($img, 'center');
            $background_thumb->save($path.$name.'_thumbnail.jpg');

            $img2->resize(null, $app->config->get('images.deal.image.height'), function ($c) {
            	$c->aspectRatio();
            });

            $background->insert($img2, 'center');
            $background->save($path.$name.'.jpg');

            $img3->resize(null, $app->config->get('images.deal.medium.height'), function ($c) {
            	$c->aspectRatio();
            });

            $background_medium->insert($img3, 'center');
            $background_medium->save($path.$name.'_medium.jpg');

            unlink($handle->file_dst_pathname);
	  		$handle->clean();

            echo json_encode(array( 
                'success' => 200,
                'filename' => $name,
            ));

		} else {
			echo json_encode(array(
				'error'  => 400, 
				'msg' => $handle->error
			));
			$handle->clean();
		}

	}

})->name('admin_deals_upload');



/*
Deals delete tmp upload post
*/
$app->post('/admin/deals/upload/tmp/image/delete',  $adminAuth(true), function() use ($app) {

	$file = APP_PATH.$app->config->get('app.tmp').$app->request->post('id');

	if (file_exists($file.'.jpg')) {
		unlink($file.'.jpg');
		unlink($file.'_thumbnail.jpg');
	}

})->name('admin_deals_upload_tmp_image_delete');

/*
Deals update get
*/
$app->get('/admin/deals/update/:id',  $adminAuth(true), function($id) use ($app) {

	$deal = Deal::find($id);

	if ($deal) {

		$sources = Source::all();
		$categories = Category::orderBy('depth', 'ASC')->get()->toArray();

		$app->render('/admin/deals/updateDeal.php', [
			'sources' => $sources,
			'categories' => CategoryHelper::buildTree($categories),
			'deal' => $deal,
			'dealCategories' => $deal->categories,
			'expirey' => ($deal->type == "count") ? date("d-m-Y H:i:s", strtotime($deal->expirey) ) : NULL,
			'dealImages' => $deal->checkImagesisExists()
		]);

	} else {
		$app->notFound();
	}

})->name('admin_deals_update');

/*
Deals update post
*/
$app->post('/admin/deals/update/:id',  $adminAuth(true), function($id) use ($app) {

	$deal = Deal::find($id);

	if ($deal) {

		$request = $app->request;
		$v = $app->validator;

		$sources = Source::all();
		$category = Category::all();

		if (CategoryHelper::validateCategory($request->post('category')) != 1) {
			throw new \Exception("Somting went wrong, try again");
		}

		if ($request->post('type') == "count") {
			$v->validate([
				'source' => [$request->post('source'), 'required|source'],
				'category' => [$request->post('category'), 'required|array'],
				'title' => [$request->post('title'), 'required|max(250)|min(20)'],
				'description' => [$request->post('description'), 'required'],
				'price' => [$request->post('price'), 'required|number'],
				'discount' => [$request->post('discount'), 'number'],
				'url' => [$request->post('url'), 'required|url'],
				'type' => [$request->post('type'), 'required|dealType'],
				'expirey' => [$request->post('expirey'), 'required'],
			]);
		} else {
			$v->validate([
				'source' => [$request->post('source'), 'required|source'],
				'category' => [$request->post('category'), 'required|array'],
				'title' => [$request->post('title'), 'required|max(250)|min(20)'],
				'description' => [$request->post('description'), 'required'],
				'price' => [$request->post('price'), 'required|number'],
				'discount' => [$request->post('discount'), 'number'],
				'url' => [$request->post('url'), 'required|url'],
				'type' => [$request->post('type'), 'required|dealType'],
			]);
		}

		if ($v->passes()) {

			$deal->update([
				'source_id' => $request->post('source'),
				'title' => $request->post('title'),
				'description' => base64_encode($request->post('description')),
				'price' => $request->post('price'),
				'discount' => $request->post('discount'),
				'url' => $request->post('url'),
				'type' => $request->post('type'),
				'expirey' => ($request->post('type') == "count") ? date("Y-m-d H:i:s", strtotime($request->post('expirey'))) : NULL,
				'featured' =>  ($request->post('featured') == "on") ? 1 : 0
			]);

			$deal->categories()->sync($request->post('category'));

			$app->flash('success', 'Deal Updated successful');
			$app->response->redirect($app->urlFor('admin_deals_update', array('id' => $deal->id) ));

		}

		$app->render('/admin/deals/updateDeal.php', [
			'sources' => $sources,
			'categories' => $category,
			'deal' => $deal,
			'dealCategories' => $deal->categories,
			'expirey' => ($deal->type == "count") ? date("d-m-Y H:i:s", strtotime($deal->expirey) ) : NULL,
			'dealImages' => $deal->checkImagesisExists(),
			'request' => $request,
			'errors' => $v->errors(),
		]);

	}else {
		$app->notFound();
	}

});

/*
Deals update upload image
*/
$app->post('/admin/deals/update/upload/:id',  $adminAuth(true), function($id) use ($app) {

	$deal = Deal::find($id);

	if ($deal) {

		if (count($deal->images) < 5) {
			$handle = new upload($_FILES['file']);

			if ($handle->uploaded) {

		  		$path = APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id.'/';

		  		$handle->file_new_name_body = uniqid(time()+3600);
		  		$handle->allowed = array('image/*');
		  		$handle->forbidden = array('application/*');
		  		$handle->file_max_size = '2000000';
		  		$handle->process($path);

				if ($handle->processed) {

					$name = $app->hash->hash($app->randomlib->generateString(32));
			  		$file = $app->config->get('app.url').$app->config->get('app.deals').'deal/'.$deal->id.'/'.$handle->file_dst_name_body.'.'.$handle->file_dst_name_ext;
			  	
			  		$background = Image::canvas($app->config->get('images.deal.image.width'), $app->config->get('images.deal.image.height'), '#ffffff' );
			  		$background_medium = Image::canvas($app->config->get('images.deal.medium.width'), $app->config->get('images.deal.medium.height'), '#ffffff' );
	  				$background_thumb = Image::canvas($app->config->get('images.deal.thumbnail.width'), $app->config->get('images.deal.thumbnail.height'), '#ffffff' );

					$img = Image::make($file);
					$img2 = Image::make($file);
					$img3 = Image::make($file);

					$img->resize(null, $app->config->get('images.deal.thumbnail.height'), function ($c) {
		            	$c->aspectRatio();
		            });

		            $background_thumb->insert($img, 'center');
		            $background_thumb->save($path.$name.'_thumbnail.jpg');

		            $img2->resize(null, $app->config->get('images.deal.image.height'), function ($c) {
		            	$c->aspectRatio();
		            });

		            $background->insert($img2, 'center');
		            $background->save($path.$name.'.jpg');

		            $img3->resize(null, $app->config->get('images.deal.medium.height'), function ($c) {
		            	$c->aspectRatio();
		            });


		            $background_medium->insert($img3, 'center');
		            $background_medium->save($path.$name.'_medium.jpg');

		            unlink($handle->file_dst_pathname);
			  		$handle->clean();

			  		$deal_images = $deal->images()->create([
			  			'image' => $app->config->get('app.deals').'deal/'.$deal->id.'/'.$name
			  		]);

		            echo json_encode(array( 
		                'success' => 200,
		                'filename' => $app->config->get('app.deals').'deal/'.$deal->id.'/'.$name,
		                'id' => $deal_images->id
		            ));

				} else {
					echo json_encode(array(
						'error'  => 400, 
						'msg' => $handle->error
					));
					$handle->clean();
				}
			}
		}

	}else {
		$app->notFound();
	}


})->name('admin_deals_update_upload');

/*
Deals update delete image
*/
$app->post('/admin/deals/update/delete/image/',  $adminAuth(true), function() use ($app) {

	$image = DealImage::find($app->request->post('id'));

	if ($image) {

		$file = APP_PATH.$image->image;

		if (file_exists($file.'.jpg')) {
			$image->delete();
			unlink($file.'.jpg');
			unlink($file.'_medium.jpg');
			unlink($file.'_thumbnail.jpg');
		}

	}

})->name('admin_deals_update_image_delete');

/*
Deals manage get
*/
$app->get('/admin/deals/manage',  $adminAuth(true), function() use ($app) {

	$sources = Source::all();
	$categories = Category::orderBy('depth', 'ASC')->get()->toArray();

	$app->render('/admin/deals/manageDeal.php', [
		'sources' => $sources,
		'categories' => CategoryHelper::buildTree($categories),
	]);

})->name('admin_deals_manage');


/*
Deals manage post
*/
$app->post('/admin/deals/manage',  $adminAuth(true), function() use ($app) {

	$deal = Deal::query();

	if ($app->request->post('source') != "" || $app->request->post('source') != null) {
		$deal->where('source_id', $app->request->post('source'));
	}

	if ($app->request->post('category') != "" || $app->request->post('category') != null) {

		$deal->whereHas('categories', function($q) use ($app)
		{
		    $q->where('category_id', '=', $app->request->post('category'));

		});
	}

	if ($app->request->post('dtype') != "" || $app->request->post('dtype') != null) {
		if ($app->request->post('dtype') == 'featured') {
			$deal->where('featured', 1);
		} else {
			$deal->where('type', $app->request->post('dtype'));
		}
	}

	$dataTable = new DataTable($deal->orderBy('id', 'DESC'), ['id', 'source_id', 'title', 'description', 'type', 'expirey', 'created_at']);

    $dataTable->setFormatRowFunction(function ($deal) use ($app){
      return [
      	$deal->getImageForTable($app->config->get('app.url'), $app->config->get('app.images')),
        $deal->source->name,
        $deal->getCateoriesForTable(),
        $deal->limiText($deal->title, 20),
        $deal->limiText($deal->description, 20),
        $deal->type,
        $deal->created_at->diffForHumans(),
        $deal->getExpirey(),
        $deal->id,
        ($deal->type == "count" && Carbon::createFromFormat('Y-m-d H:i:s', $deal->expirey) < Carbon::now()) ? 1 : 0
      ];
    });

	echo json_encode($dataTable->make());

});

/*
Deal delete get
*/
$app->get('/admin/deals/delete/:id',  $adminAuth(true), $admin(), function($id) use ($app) {

	$deal = Deal::find($id);

	if ($deal) {

		$deal->categories()->detach();

		if ($deal->images->count()) {

			if (is_dir(APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id))
		    {
		        foreach ($deal->images as $image)
		        {
		        	if (file_exists(APP_PATH.$image->image.'.jpg')) {
		            	unlink(APP_PATH.$image->image.'.jpg');
		            	unlink(APP_PATH.$image->image.'_medium.jpg');
		            	unlink(APP_PATH.$image->image.'_thumbnail.jpg');
		        	}
		        }
		        rmdir(APP_PATH.$app->config->get('app.deals').'deal/'.$deal->id);
		    }

		    $deal->images()->delete();
		}

		$deal->rates()->delete();
		$deal->comments()->delete();
		$deal->users()->delete();

		$deal->delete();

		$app->flash('success', 'Deal Deleted successful');
		$app->response->redirect($app->urlFor('admin_deals_manage'));

	} else {
		$app->notFound();
	}

})->name('admin_deals_delete');

/*
Deal comment manage post
*/
$app->post('/admin/deals/comment/manage', $adminAuth(true), function() use ($app) {

	$comment = DealComment::query();

	if ($app->request->post('cstatus') != "" || $app->request->post('cstatus') != null) {
		$comment->where('status', $app->request->post('cstatus'));
	}

	$dataTable = new DataTable($comment->orderBy('id', 'DESC'), ['id', 'deal_id', 'user_id', 'ip', 'status', 'body']);

    $dataTable->setFormatRowFunction(function ($comment) use ($app){

      return [
      	$comment->user->getAvatar($app->config->get('app.url'), $app->config->get('app.images')),
        $comment->body,
        $comment->ip,
        $app->urlFor('deal_single_page', [
        	'slug' => $comment->deal->dealUrl(), 
        	'id' => $comment->deal->id
        ]),
        $comment->id,
        $comment->status,
        $app->urlFor('admin_users_update', [
        	'id' => $comment->user->id
        ])
      ];
    });

	echo json_encode($dataTable->make());
})->name('admin_deal_comment_manage');

/*
Deal comment get body post
*/
$app->post('/admin/deals/comment/get', $adminAuth(true), function() use ($app) {

	if ($id = $app->request->post('id')) {

		$comment = DealComment::find($id);

		if ($comment) {

			echo json_encode(array(
				'body' => $comment->body,
				'status' => 200
			));	
		
		} else {
			$app->notFound();
		}

	} else {

		$app->notFound();
	}
	
})->name('admin_deal_comment_body');

/*
Deal comment save body post
*/
$app->post('/admin/deals/comment/save', $adminAuth(true), function() use ($app) {

	if ($id = $app->request->post('id')) {

		$comment = DealComment::find($id);

		if ($comment) {

			$v = $app->validator;

			$v->validate([
				'comment' => [$app->request->post('comment'), 'required|max(250)'],		
			]);

			if ($v->passes()) {

				$comment->update([
					'body' => $app->request->post('comment')
				]);
				echo json_encode(array(
					'status' => 200
				));	

			}else {
				echo json_encode(array(
					'status' => 400,
				));	
			}
		
		} else {
			$app->notFound();
		}

	} else {

		$app->notFound();
	}

})->name('admin_deal_comment_save');


/*
Deal comment delete
*/
$app->post('/admin/deals/comment/delete', $adminAuth(true), function() use ($app) {

	if ($id = $app->request->post('id')) {

		$comment = DealComment::find($id);

		if ($comment) {

			$comment->delete();

			echo json_encode(array(
				'status' => 200
			));	
		
		} else {
			$app->notFound();
		}

	} else {

		$app->notFound();
	}

})->name('admin_deal_comment_delete');