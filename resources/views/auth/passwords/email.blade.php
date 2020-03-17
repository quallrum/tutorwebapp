@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
    <script src="/js/forgetPassword.js"></script>
@endsection

@section('content')
    <section class="container forgetPassword">
		@include('shared.errors')
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
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
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
