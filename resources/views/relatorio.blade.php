<div class="relatorio col-lg-12 col-md-12 col-sm-12" v-if="consulta === 'relatorio' && cargando === false">
	<div class="table-responsive-xs table-responsive-sm" v-for="(relatorio, key) in relatorios" :key="key">
		<table class="table table-sm table-striped table-inverse">
			<thead>
				<tr>
					<th colspan="5">@{{relatorio.no_usuario}}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Período</td>
					<td>Receita Líquida</td>
					<td>Custo Fixo</td>
					<td>Comissão</td>
					<td>Lucro</td>
				</tr>
				<tr v-for="(mes, key) in relatorio.meses" :key="key">
					<td>@{{mes.fecha}}</td>
					<td>R$ @{{mes.receita_liquida}}</td>
					<td>-R$ @{{mes.custo_fixo}}</td>
					<td>-R$ @{{mes.comissao}}</td>
					<td>-R$ @{{mes.lucro}}</td>
				</tr>
				<tr>
					<td>Saldo</td>
					<td>R$ @{{relatorio.totales.receita_liquida}}</td>
					<td>-R$ @{{relatorio.totales.custo_fixo}}</td>
					<td>-R$ @{{relatorio.totales.comissao}}</td>
					<td>-R$ @{{relatorio.totales.lucro}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>