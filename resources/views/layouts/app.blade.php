<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
	<style type="text/css">
		.dropdown-hover:hover > .dropdown-menu {
			display: block;
		}
		.dropdown-hover .dropdown-menu {
			top: calc(100% + -4px);
		}
		.navbar {
			box-shadow: 0px 0px 3px 0px #c0c0c0;
		}
		.select2 {
			width: 100%;
		}
		.table-form tr td:first-child {
			width: 50px;
		}
		.table-form tr td.table-buttons {
			width: 150px;
		}
		.canvas-holder {
			margin: auto;
			width: 50%;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-light bg-light navbar-expand-lg sticky-top">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
			&#9776;
		</button>
		<a class="navbar-brand" href="#">
			<img src="{{ asset('img/logo.gif') }}" height="30" alt="Agence">
		</a>
		<div class="collapse navbar-collapse" id="exCollapsingNavbar">
			<ul class="navbar-nav mr-auto"></ul>
			<ul class="navbar-nav my-2 my-lg-0">
				<li class="nav-item dropdown dropdown-hover">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expended="false">
						Usuario
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="#">Action</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Salir</a>
				</li>
			</ul>
		</div>
	</nav>
	@yield('content')
<script src="{{ asset('js/app.js') }}"></script>
<!-- <script type="text/javascript">
	window.chartColors = {
		red: 'rgb(255, 99, 132)',
		orange: 'rgb(255, 159, 64)',
		yellow: 'rgb(255, 205, 86)',
		green: 'rgb(75, 192, 192)',
		blue: 'rgb(54, 162, 235)',
		purple: 'rgb(153, 102, 255)',
		grey: 'rgb(201, 203, 207)'
	};

	var randomScalingFactor = function() {
			return Math.round(Math.random() * 100);
		};

		var config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
					],
					backgroundColor: [
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.yellow,
						window.chartColors.green,
						window.chartColors.blue,
					],
					label: 'Dataset 1'
				}],
				labels: [
					'Red',
					'Orange',
					'Yellow',
					'Green',
					'Blue'
				]
			},
			options: {
				responsive: true
			}
		};

		/*window.onload = function() {
			var ctx = document.getElementById('chart-area').getContext('2d');
			window.myPie = new Chart(ctx, config);
		};*/
</script>
<script type="text/javascript">
	var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
			datasets: [{
				label: 'Dataset 1',
				backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				]
			}, {
				label: 'Dataset 2',
				backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				]
			}]

		};

		window.onload = function() {
			var ctxVertical = document.getElementById('chart-vertical').getContext('2d');
			window.myBar = new Chart(ctxVertical, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: 'Chart.js Bar Chart'
					}
				}
			});
			var ctx = document.getElementById('chart-area').getContext('2d');
			window.myPie = new Chart(ctx, config);

		};
</script> -->
</body>
</html>