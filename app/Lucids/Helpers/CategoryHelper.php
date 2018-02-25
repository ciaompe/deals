<?php

namespace Lucids\Helpers;

use Lucids\Models\Deals\Category;

class CategoryHelper {

	/*
	Build category recursive method
	*/
	public static function buildTree(array $elements, $parentId = 0) {

	    $branch = array();

	    foreach ($elements as $element) {

	        if ($element['parent'] == $parentId) {
	            $children = self::buildTree($elements, $element['id']);
	            if ($children) {
	                $element['children'] = $children;
	            }
	            $branch[] = $element;
	        }
	    }
	    return $branch;
	}

	/*
	Validate Category method
	*/
	public static function validateCategory($categories) {

		if (is_array($categories)) {

			$status = 0;
			
			foreach ($categories as $category) {
				$cat = Category::where('id', $category);

				if ($cat->count()) {
					$status = 1;
				} else {
					$status = 0;
				}
			}

			return $status;
		}
	}

	/*
	Category tree postion update recursive method
	*/

	public static function setArray($inptz, $prnt = 0){
		$r = [];
		foreach ($inptz as $inpt) {
			$subInpt = [];

			if (isset($inpt['children'])) {
				$subInpt = self::setArray($inpt['children'], $inpt['id']);
			}

			$r[] = ['id' => $inpt['id'], 'parent' => $prnt];
			$r = array_merge($r, $subInpt);
		}
		return $r;
	}

}