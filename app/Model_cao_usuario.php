<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model_cao_fatura;

class Model_cao_usuario extends Model {
	/**
	 * El atributo para la conexion con la tabla 'cao_usuario'
	 *
	 * @var string
	 */
	protected $table = 'cao_usuario';

	/**
	 * Relación con la tabla 'cao_os'.
	 * @return object
	 */
	public function ordenesServicio(){
		return $this->hasMany('App\Model_cao_os', 'co_usuario', 'co_usuario');
	}

	/**
	 * Relación con la tabla 'coa_salario'.
	 * @return object
	 */
	public function salario(){
		return $this->hasOne('App\Model_cao_salario', 'co_usuario', 'co_usuario');
	}

	/**
	 * Retorna los valores 'receita_liquida', 'custo_fixo', 'comissao' y 'lucro'
	 * para un consultor de una fecha específica.
	 *
	 * @param  string $mesConsulta Fecha de la consulta
	 * @return array
	 */
	public function getResultadoMes($mesConsulta){
		$cao_fatura = new Model_cao_fatura;
		
		$inicio = $mesConsulta.'-01 00:00:00';
		$aux = date('Y-m-d', strtotime("{$mesConsulta} + 1 month"));
		$fin = date('Y-m-d', strtotime("{$aux} - 1 day")).' 23:59:59';
		
		/**
		 * Consulta de las ordenes de servicios relacionadas con el usuario
		 * (relación en el modelo 'Model_cao_usuario').
		 */
		$ordenesServicioQuery = $this->ordenesServicio()->select('co_os')->get();

		$ordenesServicioID = [];
		foreach($ordenesServicioQuery as $coos) {
			$ordenesServicioID[] = $coos->co_os;
		}

		/**
		 * Consulta de las facturas relacionadas a las ordenes de servicio.
		 */
		$facturas = $cao_fatura->whereIn('co_os', $ordenesServicioID)
						 ->where('data_emissao', '>=', $inicio)
						 ->where('data_emissao', '<=', $fin)
						 ->get();

		$valor = 0;
		$comissao = 0;
		
		/**
		 * Suma el total del valor y comissao.
		 */
		foreach($facturas as $factura){
			$valor+= $factura->valor - ($factura->valor * ($factura->total_imp_inc/100));
			$comissao+= ($factura->valor - ($factura->valor * ($factura->total_imp_inc/100))) * ($factura->comissao_cn/100);
		}
		/**
		 * Si el valor no es 0 retorna los datos de la consulta.
		 */
		if($valor > 0){
			$brut_salario = isset($this->salario->brut_salario) ? $this->salario->brut_salario : 0;
			/**
			 * Formateo el idioma de PHP para mostrar las fechas en Español.
			 */
			setlocale(LC_ALL, 'es_ES.UTF8');
			$dateInicio = date_create($inicio);
			
			$resultado[0]['fecha'] = ucfirst(strftime('%B %Y', strtotime($inicio)));
			$resultado[0]['receita_liquida'] = number_format($valor, 2, ',', '.');
			$resultado[0]['custo_fixo'] = number_format($brut_salario, 2, ',', '.');
			$resultado[0]['comissao'] = number_format($comissao, 2, ',', '.');
			$resultado[0]['lucro'] = number_format(($valor)-($brut_salario + $comissao), 2, ',', '.');

			$resultado[1]['receita_liquida'] = $valor;
			$resultado[1]['custo_fixo'] = $brut_salario;
			$resultado[1]['comissao'] = $comissao;
			$resultado[1]['lucro'] = ($valor)-($brut_salario + $comissao);
			return $resultado;
		} else {
			return null;
		}
	}
}