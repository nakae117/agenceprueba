<div class="col-lg-12 col-md-12 col-sm-12">
	<label class="label">Período</label>
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-5">
			<select class="form-control" v-model="mesDesde">
				<option value="01">Enero</option>
				<option value="02">Febrero</option>
				<option value="03">Marzo</option>
				<option value="04">Abril</option>
				<option value="05">Mayo</option>
				<option value="06">Junio</option>
				<option value="07">Julio</option>
				<option value="08">Agosto</option>
				<option value="09">Septiembre</option>
				<option value="10">Octubre</option>
				<option value="11">Noviembre</option>
				<option value="12">Diciembre</option>
			</select>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-5">
			<select class="form-control" v-model="yearDesde">
				@for($i = 2000; $i <= 2030; $i++)
					<option value="{{ $i }}">{{ $i }}</option>
				@endfor
			</select>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-12">
			a
		</div>
		<div class="col-lg-2 col-md-2 col-sm-5">
			<select class="form-control" v-model="mesHasta">
				<option value="01">Enero</option>
				<option value="02">Febrero</option>
				<option value="03">Marzo</option>
				<option value="04">Abril</option>
				<option value="05">Mayo</option>
				<option value="06">Junio</option>
				<option value="07">Julio</option>
				<option value="08">Agosto</option>
				<option value="09">Septiembre</option>
				<option value="10">Octubre</option>
				<option value="11">Noviembre</option>
				<option value="12">Diciembre</option>
			</select>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-5">
			<select class="form-control" v-model="yearHasta">
				@for($i = 2000; $i <= 2030; $i++)
					<option value="{{ $i }}">{{ $i }}</option>
				@endfor
			</select>
		</div>
	</div>
</div>