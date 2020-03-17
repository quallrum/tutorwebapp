@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
    <script src="/js/emailVerification.js"></script>
@endsection

@section('content')
	<section class="container emailVerification">
		@include('shared.alerts')
		<div class="emailVerification__window">
			<h2 class="emailVerification__heading">Подтверждение Email</h2>
			<div class="emailVerification__textBlock">
				<div class="emailVerification__alertInText">
					<p class="emailVerification__alertInText-text emailVerification__alertInText-text--bold">Ваш Email не подтвержен!</p>
					<p class="emailVerification__alertInText-text">Пожалуйста, подтвердите свой Email.</p>
				</div>
				<p class="emailVerification__text">Мы отправили письмо для верификации на <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>. Если вы не можете найти письмо для верификации, пожалуйста, проверьте корзину и спам.</p>
				<p class="emailVerification__text">Если вы не получили >письмо для верификации, пожалуйста, нажмите на кнопку "Отправить снова" ниже.</p>
			</div>
            <form action="{{ route('verification.resend') }}" method="post" name="sendVerificationEmailAgain">
                @csrf
				<button class="emailVerification__button" type="submit">Отправить снова</button>
			</form>
		</div>
	</section>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
