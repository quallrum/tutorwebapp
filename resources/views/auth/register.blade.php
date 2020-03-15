@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="css/main.min.css"/>
@endsection

@section('scripts')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="js/registration.js"></script>
@endsection

@section('content')
	<section class="registration container">
		@include('shared.alerts')
		<div class="register" id="registerWindow">
			<h2 class="register__heading">Создать аккаунт</h2><a class="register__enter" href="" id="registerLinkToEnter">вход</a>
			<form class="register__form" action="{{ route('register') }}" method="post" name="registerForm" id="registartionForm">
				@csrf
				<label class="register__emailLabel" id="registerEmailLabel">
					<p class="register__text" id="registerTextEmail">Email</p>
					<input type="email" name="email" placeholder="Email" id="registerEmail" required="required" autocomplete="email"/>
					<div class="register__tick--email"></div>
					<div class="register__wrapUnderInput register__wrapUnderInput--email">
						<p class="register__capture" id="registerCapture">Мы не будем отправлять Вам спам или передавать базу данных</p>
						<p class="register__warning register__warning--email" id="registerWarningEmail">Заполните это поле, пожалуйста</p>
						<div class="register__nextButton" id="registerNextButton">Далее</div>
					</div>
				</label>
				<label class="register__passwordLabel" id="registerPasswordLabel">
					<p class="register__text" id="registerTextPassword">Password</p>
					<input type="password" name="password" placeholder="Password" id="registerPassword" autocomplete="new-password"/>
					<div class="register__tick--password"></div>
					<div class="register__wrapUnderInput register__wrapUnderInput--password">
						<p class="register__warning register__warning--password" id="registerWarningPassword">Пароль должен содержать не менее 4 символов.</p>
						<button class="register__submitButton" type="submit" id="registerSubmit">Далее</button>
					</div>
				</label>
			</form>
		</div>
	</section>
@endsection

{{-- @section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('Register') }}</div>

				<div class="card-body">
					<form method="POST" action="{{ route('register') }}">
						@csrf

						<div class="form-group row">
							<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

							<div class="col-md-6">
								<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

								@error('name')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

							<div class="col-md-6">
								<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

								@error('email')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

							<div class="col-md-6">
								<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

								@error('password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

							<div class="col-md-6">
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
							</div>
						</div>

						<div class="form-group row mb-0">
							<div class="col-md-6 offset-md-4">
								<button type="submit" class="btn btn-primary">
									{{ __('Register') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection --}}
