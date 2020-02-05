@extends('layouts.app')

@section('content')

{{-- <<div class="col-sm-12">
  	
</div> --}}
<div class="content" id="app">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-5 col-md-12 col-sm-6">
				<table class="table table-inverse">
					<thead>
						<tr>
							<th>Por consultor</th>
							<th><a href="#">Por cliente</a></th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="col-lg-10 col-sm-10 col-dm-10">
				<div class="row">
					@include('periodo')
					@include('consultores')
				</div>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2">
				<button class="btn btn-primary btn-block btn-sm" @click="getRelatorio()">Relatório</button><br>
				<button class="btn btn-secondary btn-block btn-sm" @click="getDataBar()">Gráfico</button><br>
				<button class="btn btn-danger btn-block btn-sm">Pizza</button><br>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12" v-if="cargando == true">
				<div class="alert alert-primary" role="alert">
					Cargando
				</div>
			</div>
			@include('relatorio')
			@include('bar')
		</div>
	</div>

	{{-- <div class="canvas-holder">
		<canvas id="chart-area"></canvas>
	</div>

	<div class="canvas-holder">
		<canvas id="chart-vertical"></canvas>
	</div> --}}
</div>

@endsection