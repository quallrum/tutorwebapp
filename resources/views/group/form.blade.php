@extends('layouts.app')

@section('title', $group->exists ? 'Редактирование группы '.$group->title : 'Создание группы')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/alerts.js"></script>
	<script src="/js/groupEdit.js"></script>
@endsection

@section('content')
	<section class="container-fluid groupEdit">
		<form class="groupEdit__form" action="{{ $action }}" method="post" name="groupEdit">
			@csrf
			@method($method)
			<div class="groupEdit__heading">
				<input class="groupEdit__group" type="text" name="title" value="{{ $group->title ?? '' }}" autofocus="autofocus"/>
			</div>
			<label class="groupEdit__monitor-label" for="groupEditMonitor">Выберите старосту</label>
			<select class="groupEdit__monitor" name="monitor" id="groupEditMonitor">
				@foreach ($group->students as $student)
					@if ($student->id == $group->ms_id)
						<option selected value="{{ $student->id }}">{{ $student->fullname }}</option>
					@else
						<option value="{{ $student->id }}">{{ $student->fullname }}</option>
					@endif
				@endforeach
			</select>
			@if ($group->students)
				<div class="groupEdit__table">
					<div class="groupEdit__table-item groupEdit__table-item--heading">ФИО</div>
					@foreach ($group->students as $student)
						<div class="groupEdit__table-item" data-id="{{ $student->id }}"> 
							<input class="name" type="text" name="students[{{ $student->id }}][lastname]" placeholder="Фамилия" value="{{ $student->lastname }}"/>
							<input class="name" type="text" name="students[{{ $student->id }}][firstname]" placeholder="Имя" value="{{ $student->firstname }}"/>
							<input class="name" type="text" name="students[{{ $student->id }}][fathername]" placeholder="Отчество" value="{{ $student->fathername }}"/>
							<img class="groupEdit__table-item-delete" src="/img/bin.svg" alt="delete"/>
						</div>
					@endforeach
				</div>
			@endif
			<div class="groupEdit__addStudent" id="addStudentButton"><img src="/img/plusSign.svg" alt="add"/></div>
			<div class="groupEdit__buttons">
				<button class="groupEdit__submit" type="submit">Сохранить</button>
				<div class="groupEdit__reload" id="reloadButton">отменить</div>
			</div>
		</form>
		@if ($group->exists)
			<div class="container-fluid groupData">
				<h2 class="groupData__heading">Данные аккаунта группы</h2>
				<div class="groupData__email">
					<p class="home__email-text" id="groupDataEmail">{{ $group->user->email }}</p>
					<img class="groupData__email-img" src="/img/profileEdit.svg" alt="Редактировать" id="editEmailButton"/>
				</div>
				<div class="groupData__password">
					<p class="home__password-text">●●●●●●●●●●</p>
					<img class="groupData__password-img" src="/img/profileEdit.svg" alt="Редактировать" id="editPasswordButton"/>
				</div>
				<div class="groupData__editEmail" id="editEmailSection">
					<div class="groupData__editEmail-window">
						<h2 class="groupData__editEmail-heading">Редактировать Email</h2>
						<form class="groupData__editEmail-form" action="{{ route('group.email', ['group'=>$group->id]) }}" method="post" name="editEmail">
							@csrf
							<label>
								<p class="groupData__editEmail-label">Email</p>
								<input class="groupData__editEmail-input" type="email" name="email" id="editEmailInput" value="{{ $group->user->email }}"/>
							</label>
							<button class="groupData__editEmail-submit" type="submit">Готово</button>
						</form>
						<div class="groupData__editEmail-cross" id="editEmailCross">&#9587</div>
					</div>
				</div>
				<div class="groupData__editPassword" id="editPasswordSection">
					<div class="groupData__editPassword-window">
						<h2 class="groupData__editPassword-heading">Редактировать пароль</h2>
						<form class="groupData__editPassword-form" action="{{ route('group.password', ['group'=>$group->id]) }}" method="post" name="editPassword">
							@csrf
							<label class="groupData__editPassword-item">
								<p class="groupData__editPassword-text">Новый пароль</p>
								<input class="groupData__editPassword-input" type="password" name="password" id="passwordInput" autocomplete="new-password"/>
								<p class="groupData__editPassword-capture" id="passwordLength">Пароль должен содержать не менее 8 символов.</p>
							</label>
							<label class="groupData__editPassword-item">
								<p class="groupData__editPassword-text">Повторите новый пароль</p>
								<input class="groupData__editPassword-input" type="password" name="password_confirmation" id="passwordRepeatInput" autocomplete="new-password"/>
								<p class="groupData__editPassword-capture" id="passwordsAreNotTheSame">Пароли не совпадают.</p>
							</label>
							<button class="groupData__editPassword-submit" type="submit">Готово</button>
						</form>
						<div class="groupData__editPassword-cross" id="editPasswordCross">&#9587</div>
					</div>
				</div>
			</div>
		@endif
	</section>
@endsection