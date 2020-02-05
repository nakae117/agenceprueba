<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Model_cao_os extends Model {
	protected $table = 'cao_os';

	public function faturas(){
		return $this->hasMany('App\Model_cao_fatura', 'co_os', 'co_os');
	}
}