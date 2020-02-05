<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Model_cao_os extends Model {
	/**
	 * El atributo para la conexion con la tabla 'cao_os'
	 *
	 * @var string
	 */
	protected $table = 'cao_os';

	/**
	 * Relación con la tabla 'co_os'.
	 * @return object
	 */
	public function faturas(){
		return $this->hasMany('App\Model_cao_fatura', 'co_os', 'co_os');
	}
}