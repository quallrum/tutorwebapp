@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
    <script src="/js/forgetPassword.js"></script>
@endsection

@section('content')
    <section class="container-fluid forgetPassword">
		<div class="forgetPassword__window">
			<h2 class="forgetPassword__heading">Восстановление пароля</h2>
			<form class="forgetPassword__form" action="{{ route('password.email') }}" method="post" name="forgetPassword">
                @csrf
				<label>
					<p class="forgetPassword__text">Email</p>
					<input class="forgetPassword__input" type="email" name="email" autocomplete="email" id="forgetPasswordInput"/>
				</label>
				<button class="forgetPassword__submit" type="submit">Готово</button>
			</form>
		</div>
	</section>
@endsection
