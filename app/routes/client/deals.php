<?php

use Lucids\Models\Deals\Deal;
use Lucids\Models\Deals\DealRate;
use Lucids\Models\Deals\UserDeal;
use Lucids\Models\Deals\DealComment;
use Carbon\Carbon;
use ReCaptcha\ReCaptcha;

/*
Deals get route
*/
$app->get('/deals/', function() use ($app) {
	$page = $app->request->get('page');
	$per_page = $app->config->get('perpage.deals');
	$page = (isset($page) && is_numeric($page) ) ? $page : 1;
	$count = Deal::expirey()->get()->count();
	$start = ($page-1) * $per_page;
	$deals = Deal::limit($per_page)->offset($start)
									->expirey()
									->orderBy('updated_at', 'DESC')
									->get();
	$app->render('client/deals/home.php', [
		'deals' => $deals,
		'page' => $page,
		'pages' => ceil($count / $per_page)
	]);
})->name('deal_home');
/*
Deal Single get route
*/
$app->get('/deal/:slug/:id', function($slug, $id) use ($app) {

	$deal = Deal::find($id);

	if ($deal && $slug == $deal->dealUrl()) {

		$page = $app->request->get('page');
		$per_page = $app->config->get('perpage.comments');

		$page = (isset($page) && is_numeric($page) ) ? $page : 1;

	    $comments = $deal->comments()->where('status', 1);

		$count = $comments->count();

		$start = ($page-1) * $per_page;

		$comments = $comments->limit($per_page)->offset($start)
											->orderBy('id', 'DESC')
											->with('user')->get();
		$app->render('client/deals/deal.php', [
			'deal' => $deal,
			'expirey' => ($deal->type == "count") ? date("m/d/Y H:i:s", strtotime($deal->expirey) ) : NULL,
			'comment_count' =>$count, 
			'comments' => $comments,
			'page' => $page,
			'pages' => ceil($count / $per_page)
		]);
		
	} else {
		$app->notFound();
	}

})->name('deal_single_page');


/*
Deal like get
*/
$app->post('/deal/like/', $authCheck(true), $activated(), function() use ($app) {

	$mode = $app->request->post('mode');
	$deal = Deal::find($app->request->post('id'));

	if ($deal) {

	$like = DealRate::where('deal_id', $app->request->post('id'))->where('user_id', $app->auth->id)->where('rate', 1);
	$dislike = DealRate::where('deal_id', $app->request->post('id'))->where('user_id', $app->auth->id)->where('rate', 2);

	switch ($mode) {

		case 'like':
			if (!$like->count() && !$dislike->count()) {
				$deal->rates()->create([
					'user_id' => $app->auth->id,
					'rate' => 1
				]);
			}
			if ($dislike->count()) {
				$deal->rates()->where('user_id', $app->auth->id)->update([
					'rate' => 1
				]);
			}
			break;

		case 'dislike':
			if (!$like->count() && !$dislike->count()) {
				$deal->rates()->create([
					'user_id' => $app->auth->id,
					'rate' => 1
				]);
			}
			if ($like->count()) {
				$deal->rates()->where('user_id', $app->auth->id)->update([
					'rate' => 2
				]);
			}
			break;
		case 'remove':
			if ($like->count() || $dislike->count()) {
				$deal->rates()->where('user_id', $app->auth->id)->delete();
			}

		break;
		
		default:
			exit();
			break;
	}

	} else {
		$app->notFound();
	}

})->name('deal_like');

/*
Add to watch list
*/
$app->get('/deals/add/list/:id', $authCheck(true), $activated(), function($id) use ($app) {

	$deal = Deal::find($id);

	if($deal) {

		$userDeal = UserDeal::where('deal_id', $deal->id)->where('user_id', $app->auth->id);

		if ($userDeal->count()) {
			$app->flash('error', 'This Deal already in your watch list');
			$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ) );
		} else {
			
			if ($deal->type == "count") {
				if (Carbon::createFromFormat('Y-m-d H:i:s', $deal->expirey) <= Carbon::now()) {
					$app->flash('error', 'Cannot add expired deals to the watch list');
					$app->redirect($app->urlFor('deal_watch_list', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ) );
				}
			}

			UserDeal::create([
				'deal_id' => $deal->id,
				'user_id' => $app->auth->id
			]);

			$app->flash('success', 'Deal added to the watch list');
			$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ) );
		}

	} else {
		$app->notFound();
	}

})->name('deal_add_list');


/*
Remove in watch list
*/
$app->get('/deals/remove/list/:id', $authCheck(true), $activated(), function($id) use ($app) {

	$deal = Deal::find($id);

	if($deal) {

		$userDeal = UserDeal::where('deal_id', $deal->id)->where('user_id', $app->auth->id);

		if ($userDeal->count()) {
			
			$userDeal->delete();
			$app->flash('success', 'Deal removed in your watch list');

			if ($app->request->get('outside') == false) {

				$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ) );
			}else {
				$app->redirect($app->urlFor('myaccount'));
			}
		
		} else {
			$app->flash('error', 'Deal not found in your watch list');
			$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ) );
		}

	} else {

		$app->notFound();
	}

})->name('deal_remove_list');


/*
Deal add comment post
*/
$app->get('/deals/comment/add/:id', $authCheck(true), $activated(), function($dealId) use ($app) {

	$deal = Deal::find($dealId);

	if($deal) {
		$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ).'#reviews' );
	}else {
		$app->notFound();
	}


})->name('deal_add_comment');

/*
Deal add comment post
*/
$app->post('/deals/comment/add/:id', $authCheck(true), $activated(), function($dealId) use ($app) {

	$deal = Deal::find($dealId);

	if($deal) {

		$request = $app->request;
		$v = $app->validator;

		$v->validate([
			'comment' => [$request->post('comment'), 'required|max(250)'],		
		]);

		$date = new DateTime;
		$date->modify('-1 minutes');
		$formatted_date = $date->format('Y-m-d H:i:s');

		$spam = DealComment::where('ip', $request->getIp())->where('user_id', $app->auth->id)->where('deal_id', $deal->id)->where('created_at', '>=', $formatted_date);

		if ($v->passes() && empty($request->post('user')) && !$spam->count()){

			$deal->comments()->create([
				'body' => $request->post('comment'),
				'ip' =>  $request->getIp(),
				'user_id' => $app->auth->id
			]);
			
			$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ).'#reviews' );
		}

		if($v->passes() && empty($request->post('user')) && $spam->count()) {
			$recaptcha = new ReCaptcha($app->config->get('recaptcha.secret'));
			$resp = $recaptcha->verify($request->post('g-recaptcha-response'), $request->getIp());

			if ($resp->isSuccess()) {
				
				$deal->comments()->create([
					'body' => $request->post('comment'),
					'ip' =>  $request->getIp(),
					'user_id' => $app->auth->id
				]);
				$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ).'#reviews' );
			}
		}

		$app->render('client/deals/deal.php', [
			'deal' => $deal,
			'expirey' => ($deal->type == "count") ? date("m/d/Y H:i:s", strtotime($deal->expirey) ) : NULL,
			'errors' => $v->errors(),
			'request' => $request,
			'spam' => $spam->count() ? true : false
		]);

	}else {
		$app->notFound();
	}

});


/*
Deal edit comment post
*/
$app->post('/deals/comment/edit/', $authCheck(true), $activated(), function() use ($app) {

	$comment = DealComment::find($app->request->post('id'));

	if ($comment) {
		
		if ($comment->user->id == $app->auth->id || $app->auth->isBackend()) {

			$v = $app->validator;

			$v->validate([
				'comment' => [$app->request->post('comment'), 'required|max(250)'],		
			]);

			if ($v->passes()) {

				$comment->update([
					'body' => $app->request->post('comment')
				]);
				echo json_encode(array(
					'status' => 200,
				));	

			} else {
				echo json_encode(array(
					'status' => 400,
				));	
			}
			
		}
	}

})->name('deal_edit_comment');


/*
Deal delete comment get
*/
$app->get('/deals/comments/delete/:id', $authCheck(true), function($id) use ($app) {

	$comment = DealComment::find($id);
	$deal = Deal::find($comment->deal_id);

	if ($comment) {

		if ($app->auth->isBackend()) {
			$comment->delete();

			$app->flash('success', 'Comment deleted');
			$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ) );
		} else {
			$app->notFound();
		}

	}else {
		$app->notFound();
	}
})->name('deal_delete_comment');


/*
Deal url redirect
*/
$app->get('/deals/redirect/:id', function($id) use ($app) {

	$deal = Deal::find($id);

	if($deal) {
		if ($deal->visit($deal->url)) {
			$app->redirect($deal->url, 301);
		} else {
			$app->flash('error', 'Url doesn\'t exits');
			$app->redirect($app->urlFor('deal_single_page', array('slug' => $deal->dealUrl(), 'id' => $deal->id) ) );
		}
	} else {
		$app->notFound();
	}
})->name('deal_url_redirect');