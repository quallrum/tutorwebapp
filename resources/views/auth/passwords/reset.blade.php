@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
    <script src="/js/setNewPassword.js"></script>
@endsection

@section('content')
    <section class="container setNewPassword">
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
					<p class="setNewPassword__capture" id="passwordLength">Пароль должен содержать не менее 4 символов.</p>
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
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

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
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
