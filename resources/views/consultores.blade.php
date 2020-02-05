<div class="col-lg-12 col-md-12 col-sm-12">
	<label class="label">Consultores</label>
	<select class="select2" name="usuarios[]" multiple="multiple">
		@foreach($usuarios as $usuario)
			<option value="{{ $usuario->co_usuario }}">{{ $usuario->no_usuario }}</option>
		@endforeach
	</select>
	<br>
</div>