@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="js/authorization.js"></script>
@endsection

@section('content')
	<section class="container-fluid authorization">
		<div class="authorization__window">
			<h2 class="authorization__heading">Вход</h2>
			<form class="authorization__form" action="{{ route('login') }}" method="post" name="authorization">
				@csrf
				<label class="authorization__item">
					<p class="authorization__text">Email</p>
					<input class="authorization__input" type="text" name="email" autocomplete="nickname" id="login"/>
				</label>
				<label class="authorization__item">
					<p class="authorization__text">Пароль</p>
					<input class="authorization__input" type="password" name="password" autocomplete="current-password" id="password"/>
					<p class="authorization__captureWarning">Пароль должен содержать не менее 8 символов.</p>
				</label>
				<div class="authorization__links">
					<a class="authorization__register" href="{{ route('register') }}">Зарегистрироваться</a>
					<a class="authorization__forgetPassword" href="{{ route('password.request') }}">Забыли пароль?</a>
				</div>
				<button class="authorization__submit " type="submit" id="submit">Готово</button>
			</form>
		</div>
	</section>
@endsection