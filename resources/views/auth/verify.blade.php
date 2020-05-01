@extends('layouts.app')

@section('title', 'Подтвердите email')

@section('head')
    <link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
    <script src="/js/emailVerification.js"></script>
@endsection

@section('content')
	<section class="container-fluid emailVerification">
		<div class="emailVerification__window">
			<h2 class="emailVerification__heading">Подтвердите Email</h2>
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
@endsection
