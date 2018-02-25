<?php

namespace Lucids\Models\Deals;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Carbon\Carbon;


class DealComment extends Eloquent {

	protected $table = "deal_comments",
			  $fillable = ['user_id','body','ip'];

	public function deal() {
        return $this->belongsTo('Lucids\Models\Deals\Deal');
    }

    public function user() {
        return $this->belongsTo('Lucids\Models\Auth\User');
    }

    public function createdAt() {
    	return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->diffForHumans();
    }

}