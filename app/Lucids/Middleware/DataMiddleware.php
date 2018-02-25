<?php

namespace Lucids\Middleware;
use Slim\Middleware;

use Lucids\Helpers\CategoryHelper;

class DataMiddleware extends Middleware{

	public function call(){	

		$this->parseData([
			'categoryTree' => CategoryHelper::buildTree($this->categories()),
			'sources' => $this->sources(),
			'sidebarDeals' => $this->latestDeals(),
			'hotDeal' => $this->hotDeal()
		]);

		$this->next->call();
	}

	private function categories() {
		return \Lucids\Models\Deals\Category::orderBy('depth', 'ASC')->get()->toArray();
	}

	private function sources() {
		return \Lucids\Models\Deals\Source::orderBy('id', 'ASC')->get();
	}

	private function latestDeals() {
		return \Lucids\Models\Deals\Deal::limit(4)->expirey()->orderBy('id', 'DESC')->get();
	}

	private function hotDeal() {
		return \Lucids\Models\Deals\Deal::expirey()->orderBy('discount', 'DESC')->first();
	}

	private function parseData($data) {
		$this->app->view()->appendData($data);
	}

}