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
		.chartjs {
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
</body>
</html>