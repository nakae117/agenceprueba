<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model_cao_fatura;

class Model_cao_usuario extends Model {
	protected $table = 'cao_usuario';

	public function ordenesServicio(){
		return $this->hasMany('App\Model_cao_os', 'co_usuario', 'co_usuario');
	}

	public function salario(){
		return $this->hasOne('App\Model_cao_salario', 'co_usuario', 'co_usuario');
	}

	public function getResultadoMes($mesConsulta){
		$cao_fatura = new Model_cao_fatura;
		$inicio = $mesConsulta.'-01 00:00:00';
		$aux = date('Y-m-d', strtotime("{$mesConsulta} + 1 month"));
		$fin = date('Y-m-d', strtotime("{$aux} - 1 day")).' 23:59:59';
		$ordenesServicioQuery = $this->ordenesServicio()->select('co_os')->get();
		$ordenesServicioID = [];
		// echo $fin.'<br>';

		foreach($ordenesServicioQuery as $coos) {
			$ordenesServicioID[] = $coos->co_os;
		}
		// echo var_dump($ordenesServicioID);

		$facturas = $cao_fatura->whereIn('co_os', $ordenesServicioID)
						 ->where('data_emissao', '>=', $inicio)
						 ->where('data_emissao', '<=', $fin)
						 ->get();
		// dd($facturas);
		$valor = 0;
		$comissao = 0;
		// $total_imp_inc = 0;
		foreach($facturas as $factura){
			$valor+= $factura->valor - ($factura->valor * ($factura->total_imp_inc/100));
			$comissao+= ($factura->valor - ($factura->valor * ($factura->total_imp_inc/100))) * ($factura->comissao_cn/100);
		}
		if($valor > 0){
			$brut_salario = isset($this->salario->brut_salario) ? $this->salario->brut_salario : 0;
			$resultado[0]['fecha'] = $mesConsulta;
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