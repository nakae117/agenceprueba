<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model_cao_usuario;

class HomeController extends Controller {
	private $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
	private function generateColor(){
		return 'rgba('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).', 0.9)';
	}
	public function index(){
		$usuarios = Model_cao_usuario::join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
						->where('permissao_sistema.co_sistema', 1)
						->where('permissao_sistema.in_ativo', 'S')
						->whereIn('permissao_sistema.co_tipo_usuario', [0, 1, 2])
						->get();
		return view('index', compact('usuarios'));
	}

	public function relatorio(Model_cao_usuario $modelUsuarios){
		$usuarios = $modelUsuarios->join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
						->where('permissao_sistema.co_sistema', 1)
						->where('permissao_sistema.in_ativo', 'S')
						->whereIn('permissao_sistema.co_tipo_usuario', [0, 1, 2])
						->whereIn('cao_usuario.co_usuario', request()->usuarios)
						->get();
		$responseUsuario = [];

		$iterator = 0;
		foreach($usuarios as $usuario){
			$responseUsuario[$iterator]['no_usuario'] = $usuario->no_usuario;

			$desde = new \DateTime(request()->yearDesde.'-'.request()->mesDesde);
			$hasta = new \DateTime(request()->yearHasta.'-'.request()->mesHasta);

			$d = $desde->diff($hasta);
			$difmes = $d->format('%m');
			$pivote = $desde;
			$pivote->sub(new \DateInterval('P1M'));

			$responseUsuario[$iterator]['totales']['receita_liquida'] = 0;
			$responseUsuario[$iterator]['totales']['custo_fixo'] = 0;
			$responseUsuario[$iterator]['totales']['comissao'] = 0;
			$responseUsuario[$iterator]['totales']['lucro'] = 0;

			for($i = 0; $i <= $difmes ; $i++){ 
				$pivote->add(new \DateInterval('P1M'));
				$mesResultado = $usuario->getResultadoMes($pivote->format('Y-m'));
				if($mesResultado){
					$responseUsuario[$iterator]['meses'][] = $mesResultado[0];
					$responseUsuario[$iterator]['totales']['receita_liquida']+= $mesResultado[1]['receita_liquida'];
					$responseUsuario[$iterator]['totales']['custo_fixo']+= $mesResultado[1]['custo_fixo'];
					$responseUsuario[$iterator]['totales']['comissao']+= $mesResultado[1]['comissao'];
					$responseUsuario[$iterator]['totales']['lucro']+= $mesResultado[1]['lucro'];
				}
			}
			$responseUsuario[$iterator]['totales']['receita_liquida'] = number_format($responseUsuario[$iterator]['totales']['receita_liquida'], 2, ',', '.');
			$responseUsuario[$iterator]['totales']['custo_fixo'] = number_format($responseUsuario[$iterator]['totales']['custo_fixo'], 2, ',', '.');
			$responseUsuario[$iterator]['totales']['comissao'] = number_format($responseUsuario[$iterator]['totales']['comissao'], 2, ',', '.');
			$responseUsuario[$iterator]['totales']['lucro'] = number_format($responseUsuario[$iterator]['totales']['lucro'], 2, ',', '.');
			$iterator++;
		}

		return response()->json($responseUsuario);
	}

	public function bar(Model_cao_usuario $modelUsuarios){
		$usuarios = $modelUsuarios->join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
						->where('permissao_sistema.co_sistema', 1)
						->where('permissao_sistema.in_ativo', 'S')
						->whereIn('permissao_sistema.co_tipo_usuario', [0, 1, 2])
						->whereIn('cao_usuario.co_usuario', request()->usuarios)
						->get();

		$desdeP = new \DateTime(request()->yearDesde.'-'.request()->mesDesde);
		$hastaP = new \DateTime(request()->yearHasta.'-'.request()->mesHasta);

		$dP = $desdeP->diff($hastaP);
		$difmesP = $dP->format('%m');
		$piv = $desdeP;
		$piv->sub(new \DateInterval('P1M'));

		// $barChartData['databar'][] = [];
		$ite = 0;
		for($i = 0; $i <= $difmesP ; $i++){
			$piv->add(new \DateInterval('P1M'));
			$barChartData['databar']['labels'][] = $this->meses[((int) $piv->format('m') - 1)];
			$ite++;
		}
		$barChartData['databar']['datasets'][0]['type'] = 'line';
		$barChartData['databar']['datasets'][0]['label'] = 'Custo Fixo Medio';
		$barChartData['databar']['datasets'][0]['borderColor'] = $this->generateColor();
		$barChartData['databar']['datasets'][0]['fill'] = false;
		$barChartData['databar']['datasets'][0]['borderWidth'] = 2;

		$iterator = 1;
		$custoFixoTotal = 0;
		foreach($usuarios as $usuario){
			$barChartData['databar']['datasets'][$iterator]['type'] = 'bar';
			$barChartData['databar']['datasets'][$iterator]['label'] = $usuario->no_usuario;
			$barChartData['databar']['datasets'][$iterator]['backgroundColor'] = $this->generateColor();
			$barChartData['databar']['datasets'][$iterator]['borderColor'] = $barChartData['databar']['datasets'][$iterator]['backgroundColor'];
			$barChartData['databar']['datasets'][$iterator]['borderWidth'] = 1;


			$desde = new \DateTime(request()->yearDesde.'-'.request()->mesDesde);
			$hasta = new \DateTime(request()->yearHasta.'-'.request()->mesHasta);

			$d = $desde->diff($hasta);
			$difmes = $d->format('%m');
			$pivote = $desde;
			$pivote->sub(new \DateInterval('P1M'));

			for($i = 0; $i <= $difmes ; $i++){
				$pivote->add(new \DateInterval('P1M'));
				// echo $this->meses[((int) $pivote->format('m') - 1)].'<br>';
				$mesResultado = $usuario->getResultadoMes($pivote->format('Y-m'));
				if($mesResultado){
					$barChartData['databar']['datasets'][$iterator]['data'][] = $mesResultado[1]['receita_liquida'];
					$custoFixoTotal += $mesResultado[1]['custo_fixo'];
				}
			}
			$iterator++;
		}
		$custoFixoMedia = $custoFixoTotal/count(request()->usuarios);
		for($i = 0; $i < $ite; $i++){ 
			$barChartData['databar']['datasets'][0]['data'][$i] = $custoFixoMedia;
		}

		return response()->json($barChartData['databar']);
	}
}