<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="ie=edge"/>
	<meta name="description" content=""/>
	<meta name="theme-color" content="#000"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="#000"/>
	<link rel="shortcut icon" href="" type="image/png"/>
    @yield('head')
</head>
<body>
	<header class="container-fluid">
		<div class="container header__content">
			<a href="/"><img class="header__logo" src="/img/logo.svg" alt="logo"/></a>
			<h1 class="header__heading">Tutor's WEB API</h1>
			@auth
				<form class="journal__signOut" action="{{ route('logout') }}" method="POST" name="journalSignOut">
					@csrf
					<label>
						<img class="journal__signOut-img" src="/img/signOut.svg" alt="Sign Out"/>
						<input class="journal__signOut-submit" type="submit" value=""/>
					</label>
				</form>
			@endauth
		</div>
	</header>
	@yield('content')
	<script src="js/alerts.js"></script>
    @yield('scripts')
</body>
</html>
