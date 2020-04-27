@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
    <script src="/js/setNewPassword.js"></script>
@endsection

@section('content')
    <section class="container-fluid setNewPassword">
		@include('shared.alerts')
		<div class="setNewPassword__window">
			<h2 class="setNewPassword__heading">Установите новый пароль</h2>
			<form class="setNewPassword__form" action="{{ route('password.update') }}" method="post" name="setNewPassword">
				<label class="setNewPassword__item">
					<p class="setNewPassword__text">Email</p>
					<input class="setNewPassword__input" type="email" name="email" id="emailInput" disabled="disabled" value="{{ $email ?? old('email') }}" autocomplete="email"/>
				</label>
				<label class="setNewPassword__item">
					<p class="setNewPassword__text">Новый пароль</p>
					<input class="setNewPassword__input" type="password" name="password" id="passwordInput" autocomplete="new-password"/>
					<p class="setNewPassword__capture" id="passwordLength">Пароль должен содержать не менее 8 символов.</p>
				</label>
				<label class="setNewPassword__item">
					<p class="setNewPassword__text">Повторите новый пароль</p>
					<input class="setNewPassword__input" type="password" name="password_confirmation" id="passwordRepeatInput" autocomplete="new-password"/>
					<p class="setNewPassword__capture" id="passwordsAreNotTheSame">Пароли не совпадают.</p>
				</label>
				<button class="setNewPassword__submit" type="submit">Готово</button>
			</form>
		</div>
	</section>
@endsection
