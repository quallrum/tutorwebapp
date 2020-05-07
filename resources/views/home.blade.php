@extends('layouts.app')

@section('title', 'Главная')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
    <script src="/js/alerts.js"></script>
	<script src="/js/home.js"></script>
@endsection

@section('content')
	<section class="home container-fluid">
		<div class="home__window">
			<h2 class="home__heading">Профиль</h2>
            <p class="home__role">Вы вошли как {{ mb_strtolower($role->title) }}</p>
            @if ($role->name != 'group')
                <div class="home__email">
					<p class="home__email-text" id="emailText">{{ $user->email }}</p>
					<img class="home__email-img" src="/img/profileEdit.svg" alt="Редактировать" id="editEmailButton"/>
                </div>
                <div class="home__password">
					<p class="home__password-text">●●●●●●●●●●</p>
					<img class="home__password-img" src="/img/profileEdit.svg" alt="Редактировать" id="editPasswordButton"/>
                </div>
			@endif
			@if ($role->name == 'tutor')
				<div class="home__telegram">
					<p class="home__telegram-text" id="telegramText">{{ $tutor->telegram ? '@'.$tutor->telegram : 'Telegram'}}</p>
					<img class="home__telegram-img" src="/img/profileEdit.svg" alt="Редактировать" id="editTelegramButton"/>
				</div>
				<form class="home__fullname" action="{{ route('edit.fullname') }}" method="post" name="homeFullname">
					@csrf
					<input class="home__fullname-item" type="text" name="lastname" value="{{ $tutor->lastname }}" id="lastname"/>
					<input class="home__fullname-item" type="text" name="firstname" value="{{ $tutor->firstname }}" id="firstname"/>
					<input class="home__fullname-item" type="text" name="fathername" value="{{ $tutor->fathername }}" id="fathername"/>
					<button class="home__fullname-submit" type="submit">Сохранить</button>
				</form>
			@endif
			<div class="home__links">
                @if ($role->name == 'group' || $role->name == 'monitor')
                    <a class="home__link" href="{{ route('journal.subject', ['group' => $group->id]) }}">Журнал</a>
                    <a class="home__link" href="{{ route('mark.subject', ['group' => $group->id]) }}">Оценки</a>
				@endif
				@if ($role->name == 'monitor')
					<a class="home__link" href="{{ route('group.edit', ['group' => $group->id]) }}">Управление группой</a>
				@endif
                @can('journal.changeGroup')
                    <a class="home__link" href="{{ route('journal.group') }}">Журнал</a>
                @endcan
                @can('mark.changeGroup')
                    <a class="home__link" href="{{ route('mark.group') }}">Оценки</a>
                @endcan
				@if($role->name == 'admin')
					<a class="home__link" href="{{ route('group.index') }}">Управление группами</a>
					<a class="home__link" href="{{ route('roles.index') }}">Роли пользователей</a>
					<a class="home__link" href="{{ route('subject.index') }}">Предметы</a>
				@endif
                {{-- <a class="home__link" href="">Выбрать предмет</a> --}}
            </div>
		</div>
	</section>
	@if ($role->name != 'group')
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
						<p class="editPassword__capture" id="passwordLength">Пароль должен содержать не менее 8 символов.</p>
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
	@endif
	@if ($role->name == 'tutor')
		<section class="editTelegram" id="editTelegramSection">
			<div class="editTelegram__window">
				<h2 class="editTelegram__heading">Редактировать Telegram</h2>
				<form class="editTelegram__form" action="{{ route('edit.telegram') }}" method="post" name="editTelegram">
					@csrf
					<label>
						<p class="editTelegram__label">Telegram</p>
						<input class="editTelegram__input" type="text" name="telegram" id="editTelegramInput" value="{{ $tutor->telegram }}" placeholder="telegram_login"/>
					</label>
					<button class="editTelegram__submit" type="submit">Готово</button>
				</form>
				<div class="editTelegram__cross" id="editTelegramCross">&#9587</div>
			</div>
		</section>
	@endif
@endsection
