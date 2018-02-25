<?php

use Lucids\Models\Advertise\Adsize;
use Lucids\Models\Advertise\Adunit;
use Intervention\Image\ImageManagerStatic as Image;

/*
Advertise get
*/

$app->get('/admin/advertise', $adminAuth(true), $admin(), function() use ($app) {

	$adunits = Adunit::with('adsize')->get();

	$app->render('admin/advertise/advertise.php', [
		'units' => $adunits
	]);

})->name('admin_advertise');

/*
Advertise new get
 */
$app->get('/admin/advertise/new', $adminAuth(true), $admin(), function() use ($app) {

	unset($_SESSION['ad_image']);

	$adsizes = Adsize::all();

	$app->render('admin/advertise/adNew.php', [
		'adsizes' => $adsizes
	]);

})->name('admin_advertise_new');

/*
Advertise new post
 */
$app->post('/admin/advertise/new', $adminAuth(true), $admin(), function() use ($app) {

	$request = $app->request;
	$v = $app->validator;

	$adsizes = Adsize::all();

	$image = (isset($_SESSION['ad_image'])) ? $_SESSION['ad_image'] : NULL;

	if ($request->post('type') == "code") {
		$v->validate([
			'type' => [$request->post('type'), 'required|adType'],
			'size' => [$request->post('size'), 'required|adSize'],
			'code' => [$request->post('code'), 'required']
		]);
	} else {
		$v->validate([
			'type' => [$request->post('type'), 'required|adType'],
			'size' => [$request->post('size'), 'required|adSize'],
			'url' => [$request->post('url'), 'required|url']
		]);
	}

	$adunit = Adunit::where('adsize_id', $request->post('size'))->count();

	if ($v->passes() && !$adunit) {

		Adunit::create([
			'type' => $request->post('type'),
			'adsize_id' => $request->post('size'),
			'code' => ($request->post('type') == "code") ? base64_encode($request->post('code')) : NULL,
			'url' => ($request->post('type') == "image") ? $request->post('url') : NULL,
			'image' => ( ($request->post('type') == "image") && ($image != NULL || $image != "") ) ? $app->config->get('app.advertise').$image : NULL
		]);

		if ($image != NULL || $image != "") {
			if (file_exists(APP_PATH.$app->config->get('app.tmp').$image)) {
				rename(APP_PATH.$app->config->get('app.tmp').$image, APP_PATH.$app->config->get('app.advertise').$image);
				unset($_SESSION['ad_image']);
			}
		}

		$app->flash('success', 'Ad unit created successful');
		$app->response->redirect($app->urlFor('admin_advertise'));
	}

	$app->render('admin/advertise/adNew.php', [
		'uniterror' => ($adunit) ? 'Selected ad unit size is already exists' : NULL,
		'adsizes' => $adsizes,
		'request' => $request,
		'errors' => $v->errors(),
		'adimg' =>  ( ($image != NULL) || ($image != "") ) ? $app->config->get('app.url').$app->config->get('app.tmp').$image : NULL,
	]);

});

/*
Advertise update get
 */
$app->get('/admin/advertise/update/:id', $adminAuth(true), $admin(), function($id) use ($app) {

	unset($_SESSION['ad_image']);

	$unit = Adunit::find($id);

	if ($unit) {

		$adsizes = Adsize::all();

		$app->render('admin/advertise/adUpdate.php', [
			'adsizes' => $adsizes,
			'unit' => $unit
		]);

	} else {
		$app->notFound();
	}

})->name('admin_advertise_update');

/*
Advertise update post
 */
$app->post('/admin/advertise/update/:id', $adminAuth(true), $admin(), function($id) use ($app) {

	$unit = Adunit::find($id);

	if ($unit) {

		$request = $app->request;
		$v = $app->validator;

		$adsizes = Adsize::all();

		if ($request->post('type') == "code") {
			$v->validate([
				'type' => [$request->post('type'), 'required|adType'],
				'size' => [$request->post('size'), 'required|adSize'],
				'code' => [$request->post('code'), 'required']
			]);
		} else {
			$v->validate([
				'type' => [$request->post('type'), 'required|adType'],
				'size' => [$request->post('size'), 'required|adSize'],
				'url' => [$request->post('url'), 'required|url']
			]);
		}

		$image = (isset($_SESSION['ad_image'])) ? $_SESSION['ad_image'] : NULL;

		$adunit = Adunit::where('adsize_id', $request->post('size'))->whereNotIn('id', [$unit->id])->count();

		if ($v->passes() && !$adunit) {

			if ($request->post('type') == "code") {
				if ($unit->type == "image" && $unit->image != "") {
					if (file_exists(APP_PATH.$unit->image)) {
						unlink(APP_PATH.$unit->image);
					}
				}
			}

			if ( ($image != NULL || $image != "") && $unit->type == "image" && $unit->image != "") {
				if (file_exists(APP_PATH.$unit->image)) {
					unlink(APP_PATH.$unit->image);
				}
			}

			$unit->update([
				'type' => $request->post('type'),
				'adsize_id' => $request->post('size'),
				'code' => ($request->post('type') == "code") ? base64_encode($request->post('code')) : NULL,
				'url' => ($request->post('type') == "image") ? $request->post('url') : NULL,
				'image' => ($request->post('type') == "image" ? ( ($image != NULL || $image != "") ? $app->config->get('app.advertise').$image : $unit->image ) : NULL)
			]);

			if ($image != NULL || $image != "") {
				if (file_exists(APP_PATH.$app->config->get('app.tmp').$image)) {
					rename(APP_PATH.$app->config->get('app.tmp').$image, APP_PATH.$app->config->get('app.advertise').$image);
					unset($_SESSION['ad_image']);
				}
			}

			$app->flash('success', 'Ad unit updated successful');
			$app->response->redirect($app->urlFor('admin_advertise_update', ['id' => $unit->id]));

		}

		$app->render('admin/advertise/adUpdate.php', [
			'uniterror' => ($adunit) ? 'Selected ad unit size is already exists' : NULL,
			'adsizes' => $adsizes,
			'request' => $request,
			'errors' => $v->errors(),
			'unit' => $unit,
			'adimg' =>  ( ($image != NULL) || ($image != "") ) ? $app->config->get('app.url').$app->config->get('app.tmp').$image : NULL,
		]);


	} else {
		$app->notFound();
	}

});

/*
Advertise image upload post
 */
$app->post('/admin/advertise/image/upload', $adminAuth(true), $admin(), function() use ($app) {

	$image = (isset($_SESSION['ad_image'])) ? $_SESSION['ad_image'] : NULL;

	if ($image != NULL || $image != "") {
		if (file_exists(APP_PATH.$app->config->get('app.tmp').$image)) {
			unlink(APP_PATH.$app->config->get('app.tmp').$image);
		}
	}

	$adsize =  Adsize::find($app->request->post('adsize'));

	if ( $app->request->post('adtype') == "image") {

		if ($adsize) {

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
			  	
			  	$background = Image::canvas($adsize->width, $adsize->height, '#ffffff');

			  	$image = Image::make($file)
			  			->crop(round($imgdata->width), round($imgdata->height), round($imgdata->x), round($imgdata->y))
			  			->resize(null, $adsize->height, function ($c) {
						    $c->aspectRatio();
						});;

			  	$background->insert($image, 'center');
			  	$background->save($path.$name.'.jpg');

			  	unlink($handle->file_dst_pathname);
			  	$handle->clean();

			  	$_SESSION['ad_image'] = $name.'.jpg';
			 
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

		} else {
			echo json_encode(array(
				'state' => 200,
				'message' =>  'Please choose an Ad unit size'
			));
		}

	} else {
		echo json_encode(array(
			'state' => 200,
			'message' =>  'Ad unit type is not an image'
		));

	}

})->name('admin_advertise_image_upload');


/*
Advertise delete get
 */
$app->get('/admin/advertise/delete/:id', $adminAuth(true), $admin(), function($id) use ($app) {

	$unit = Adunit::find($id);

	if ($unit) {
		
		if ($unit->type == "image") {
			if (file_exists(APP_PATH.$unit->image)) {
				unlink(APP_PATH.$unit->image);
			}
		}

		$unit->delete();

		$app->flash('success', 'Ad unit deleted successful');
		$app->response->redirect($app->urlFor('admin_advertise'));

	} else {

		$app->notFound();

	}

})->name('admin_advertise_delete');