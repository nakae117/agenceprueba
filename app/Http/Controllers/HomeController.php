<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model_cao_usuario;

class HomeController extends Controller {
	/**
	 * Arreglo de meses para generar los datos de la tabla.
	 *
	 * @var arrya
	 */
	private $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
	
	/**
	 * Genera colore aleatorios para los gráficos.
	 *
	 * @return string
	 */
	private function generateColor(){
		return 'rgba('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).', 0.9)';
	}

	/**
	 * Muestra la vista para la consulta de los datos de los consultores.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		/**
		 * Consulta a los consultores con los permisos en el sistemas.
		 */
		$usuarios = Model_cao_usuario::join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
						->where('permissao_sistema.co_sistema', 1)
						->where('permissao_sistema.in_ativo', 'S')
						->whereIn('permissao_sistema.co_tipo_usuario', [0, 1, 2])
						->get();

		return view('index', compact('usuarios'));
	}

	/**
	 * Retorna la consulta 'Relatório'.
	 *
	 * @var object $modelUsuarios Inicializa el modelo de la tabla 'cao_usuario'
	 * @return json
	 */
	public function relatorio(Model_cao_usuario $modelUsuarios){
		/**
		 * Consulta a los consultores con los permisos en el sistemas.
		 */
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

			// Rango de fechas.
			$desde = new \DateTime(request()->yearDesde.'-'.request()->mesDesde);
			$hasta = new \DateTime(request()->yearHasta.'-'.request()->mesHasta);

			$d = $desde->diff($hasta);
			$difmes = $d->format('%m');
			$pivote = $desde;
			$pivote->sub(new \DateInterval('P1M'));

			// Totales de la consulta.
			$responseUsuario[$iterator]['totales']['receita_liquida'] = 0;
			$responseUsuario[$iterator]['totales']['custo_fixo'] = 0;
			$responseUsuario[$iterator]['totales']['comissao'] = 0;
			$responseUsuario[$iterator]['totales']['lucro'] = 0;

			/**
			 * Recorre los meses dentro del rango de la consulta, para generar los datos
			 * para mostrar en el navegador.
			 */
			for($i = 0; $i <= $difmes ; $i++){ 
				// Pivote para recorrer de uno en uno los meses. 
				$pivote->add(new \DateInterval('P1M'));
				/**
				 * Consulta los valores ('receita_liquida', 'custo_fixo', 'comissao'
				 * y 'lucro') de mes correspondiente.
				 */
				$mesResultado = $usuario->getResultadoMes($pivote->format('Y-m'));
				if($mesResultado){
					$responseUsuario[$iterator]['meses'][] = $mesResultado[0];
					$responseUsuario[$iterator]['totales']['receita_liquida']+= $mesResultado[1]['receita_liquida'];
					$responseUsuario[$iterator]['totales']['custo_fixo']+= $mesResultado[1]['custo_fixo'];
					$responseUsuario[$iterator]['totales']['comissao']+= $mesResultado[1]['comissao'];
					$responseUsuario[$iterator]['totales']['lucro']+= $mesResultado[1]['lucro'];
				}
			}
			// Formatea los datos para mostrarlos de esta manera XX.XXX,XX
			$responseUsuario[$iterator]['totales']['receita_liquida'] = number_format($responseUsuario[$iterator]['totales']['receita_liquida'], 2, ',', '.');
			$responseUsuario[$iterator]['totales']['custo_fixo'] = number_format($responseUsuario[$iterator]['totales']['custo_fixo'], 2, ',', '.');
			$responseUsuario[$iterator]['totales']['comissao'] = number_format($responseUsuario[$iterator]['totales']['comissao'], 2, ',', '.');
			$responseUsuario[$iterator]['totales']['lucro'] = number_format($responseUsuario[$iterator]['totales']['lucro'], 2, ',', '.');
			$iterator++;
		}

		return response()->json($responseUsuario);
	}

	public function bar(Model_cao_usuario $modelUsuarios){
		/**
		 * Consulta a los consultores con los permisos en el sistemas.
		 */
		$usuarios = $modelUsuarios->join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
						->where('permissao_sistema.co_sistema', 1)
						->where('permissao_sistema.in_ativo', 'S')
						->whereIn('permissao_sistema.co_tipo_usuario', [0, 1, 2])
						->whereIn('cao_usuario.co_usuario', request()->usuarios)
						->get();

		// Rango de fechas.
		$desdeP = new \DateTime(request()->yearDesde.'-'.request()->mesDesde);
		$hastaP = new \DateTime(request()->yearHasta.'-'.request()->mesHasta);

		$dP = $desdeP->diff($hastaP);
		$difmesP = $dP->format('%m');
		$piv = $desdeP;
		$piv->sub(new \DateInterval('P1M'));

		/** Recorre los meses del rango de fecha consultado para generar las
		 * etiquetas en el gráfico.
		 */
		$ite = 0;
		for($i = 0; $i <= $difmesP ; $i++){
			$piv->add(new \DateInterval('P1M'));
			$barChartData['databar']['labels'][] = $this->meses[((int) $piv->format('m') - 1)];
			$ite++;
		}
		// Opciones para llenar el gráfico (para la librería Chartjs)
		$barChartData['databar']['datasets'][0]['type'] = 'line';
		$barChartData['databar']['datasets'][0]['label'] = 'Custo Fixo Medio';
		$barChartData['databar']['datasets'][0]['borderColor'] = $this->generateColor();
		$barChartData['databar']['datasets'][0]['fill'] = false;
		$barChartData['databar']['datasets'][0]['borderWidth'] = 2;

		$iterator = 1;
		$custoFixoTotal = 0;
		foreach($usuarios as $usuario){
			// Opciones para llenar el gráfico (para la librería Chartjs)
			$barChartData['databar']['datasets'][$iterator]['type'] = 'bar';
			$barChartData['databar']['datasets'][$iterator]['label'] = $usuario->no_usuario;
			$barChartData['databar']['datasets'][$iterator]['backgroundColor'] = $this->generateColor();
			$barChartData['databar']['datasets'][$iterator]['borderColor'] = $barChartData['databar']['datasets'][$iterator]['backgroundColor'];
			$barChartData['databar']['datasets'][$iterator]['borderWidth'] = 1;


			// Rango de fechas.
			$desde = new \DateTime(request()->yearDesde.'-'.request()->mesDesde);
			$hasta = new \DateTime(request()->yearHasta.'-'.request()->mesHasta);

			$d = $desde->diff($hasta);
			$difmes = $d->format('%m');
			$pivote = $desde;
			$pivote->sub(new \DateInterval('P1M'));

			/**
			 * Recorre los meses dentro del rango de la consulta, para generar los datos
			 * para mostrar en el navegador.
			 */
			for($i = 0; $i <= $difmes ; $i++){
				// Pivote para recorrer de uno en uno los meses. 
				$pivote->add(new \DateInterval('P1M'));
				$mesActual = (int) $pivote->format('m');
				/**
				 * Consulta los valores ('receita_liquida', 'custo_fixo', 'comissao'
				 * y 'lucro') de mes correspondiente.
				 */
				$mesResultado = $usuario->getResultadoMes($pivote->format('Y-m'));
				if($mesResultado){
					// Almacena la receita liquida para la gráfica.
					$barChartData['databar']['datasets'][$iterator]['data'][$mesActual - 1] = $mesResultado[1]['receita_liquida'];
					// Total del custo fixo para generar el promedio.
					$custoFixoTotal += $mesResultado[1]['custo_fixo'];
				} else {
					$barChartData['databar']['datasets'][$iterator]['data'][$mesActual - 1] = 0;
				}
			}
			$iterator++;
		}
		// Promedio del custo fixo.
		$custoFixoMedia = $custoFixoTotal/count(request()->usuarios);
		for($i = 0; $i < $ite; $i++){ 
			// Almacena el promedio del custo fixo.
			$barChartData['databar']['datasets'][0]['data'][$i] = $custoFixoMedia;
		}

		return response()->json($barChartData['databar']);
	}

	public function pie(Model_cao_usuario $modelUsuarios){
		/**
		 * Consulta a los consultores con los permisos en el sistemas.
		 */
		$usuarios = $modelUsuarios->join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
						->where('permissao_sistema.co_sistema', 1)
						->where('permissao_sistema.in_ativo', 'S')
						->whereIn('permissao_sistema.co_tipo_usuario', [0, 1, 2])
						->whereIn('cao_usuario.co_usuario', request()->usuarios)
						->get();

		$barChartData['databar']['type'] = 'pie';
		$barChartData['databar']['data']['datasets'] = [];
		$totalReceitaLiquida = 0;

		foreach($usuarios as $usuario){
			$barChartData['databar']['data']['labels'][] = $usuario->no_usuario;

			// Rango de fechas.
			$desde = new \DateTime(request()->yearDesde.'-'.request()->mesDesde);
			$hasta = new \DateTime(request()->yearHasta.'-'.request()->mesHasta);

			$d = $desde->diff($hasta);
			$difmes = $d->format('%m');
			$pivote = $desde;
			$pivote->sub(new \DateInterval('P1M'));
			
			/**
			 * Total de la receita liquida.
			 * @var number
			 */

			$receitaLiquida = 0;
			/**
			 * Recorre los meses dentro del rango de la consulta, para generar los datos
			 * para mostrar en el navegador.
			 */
			for($i = 0; $i <= $difmes ; $i++){
				// Pivote para recorrer de uno en uno los meses. 
				$pivote->add(new \DateInterval('P1M'));
				/**
				 * Consulta los valores ('receita_liquida', 'custo_fixo',
				 * 'comissao' y 'lucro') de mes correspondiente.
				 */
				$mesResultado = $usuario->getResultadoMes($pivote->format('Y-m'));
				if($mesResultado){
					// Suma total de la receita liquida por usuario.
					$receitaLiquida += $mesResultado[1]['receita_liquida'];
				}
			}
			// Suma total de la receita liquida de todos los usuarios.
			$totalReceitaLiquida += $receitaLiquida;
			$barChartData['databar']['data']['datasets'][0]['data'][] = $receitaLiquida;
			/**
			 * Opciones para llenar el gráfico (para la librería Chartjs),
			 * para darle un color en la gráfica.
			 */
			$barChartData['databar']['data']['datasets'][0]['backgroundColor'][] = $this->generateColor();
		}
		// Opciones para llenar el gráfico (para la librería Chartjs)
		$barChartData['databar']['data']['datasets'][0]['label'] = 'Participacao';
		for ($i = 0; $i < count($barChartData['databar']['data']['datasets'][0]['data']); $i++) { 
			// Cambiar a porcentaje la receita liquida.
			$barChartData['databar']['data']['datasets'][0]['data'][$i] = (float) number_format(($barChartData['databar']['data']['datasets'][0]['data'][$i] * 100) / $totalReceitaLiquida, 2);
		}

		return response()->json($barChartData['databar']);
	}
}