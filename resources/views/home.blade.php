@extends('layouts.app')

@section('head')
    <title>Tutor Web API</title>
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
    <script src="/js/alerts.js"></script>
	<script src="/js/home.js"></script>
@endsection

@section('content')
	<section class="home">
		@include('shared.alerts')
		<div class="home__window">
			<h2 class="home__heading">Профиль</h2>
            <p class="home__role">Вы вошли как {{ mb_strtolower($role->title) }}</p>
            @if ($role->name != 'group')
                <div class="home__email">
                    <p class="home__email-text">{{ $user->email }}</p><img class="home__email-img" src="/img/profileEdit.svg" alt="Редактировать" id="editEmailButton"/>
                </div>
                <div class="home__password">
                    <p class="home__password-text">●●●●●●●●●●</p><img class="home__password-img" src="/img/profileEdit.svg" alt="Редактировать" id="editPasswordButton"/>
                </div>
            @endif
			<div class="home__links">
                @if ($role->name == 'group')
                    <a class="home__link" href="{{ route('journal.subject', ['group' => $group->id]) }}">Смотреть журнал</a>
                @endif
                @can('journal.changeGroup')
                    <a class="home__link" href="{{ route('journal.group') }}">Выбрать группу</a>
                @endcan
                {{-- <a class="home__link" href="">Выбрать предмет</a> --}}
            </div>
		</div>
	</section>
	<section class="editEmail" id="editEmailSection">
		<div class="editEmail__window">
			<h2 class="editEmail__heading">Редактировать Email</h2>
            <form class="editEmail__form" action="{{ route('edit.email') }}" method="post" name="editEmail">
                @csrf
				<label>
					<p class="editEmail__label">Email</p>
					<input class="editEmail__input" type="email" name="email" id="editEmailInput" value="{{ $user->email }}"/>
				</label>
				<button class="editEmail__submit" type="submit">Готово</button>
			</form>
			<div class="editEmail__cross" id="editEmailCross">&#9587</div>
		</div>
	</section>
	<section class="editPassword" id="editPasswordSection">
		<div class="editPassword__window">
			<h2 class="editPassword__heading">Редактировать пароль</h2>
            <form class="editPassword__form" action="{{ route('edit.password') }}" method="post" name="editPassword">
                @csrf
				<label class="editPassword__item">
					<p class="editPassword__text">Новый пароль</p>
					<input class="editPassword__input" type="password" name="password" id="passwordInput" autocomplete="new-password"/>
					<p class="editPassword__capture" id="passwordLength">Пароль должен содержать не менее 4 символов.</p>
				</label>
				<label class="editPassword__item">
					<p class="editPassword__text">Повторите новый пароль</p>
					<input class="editPassword__input" type="password" name="password_confirmation" id="passwordRepeatInput" autocomplete="new-password"/>
					<p class="editPassword__capture" id="passwordsAreNotTheSame">Пароли не совпадают.</p>
				</label>
				<button class="editPassword__submit" type="submit">Готово</button>
			</form>
			<div class="editPassword__cross" id="editPasswordCross">&#9587</div>
		</div>
	</section>
@endsection
