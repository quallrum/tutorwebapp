@extends('layouts.app')

@section('title', $group->exists ? 'Редактирование группы '.$group->title : 'Создание группы')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
	<script src="/js/alerts.js"></script>
	<script src="/js/groupEdit.js"></script>
@endsection

@section('content')
	<section class="container-fluid">
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link active" href="#groupEdit">Редактирование группы</a></li>
			@if ($group->exists)
				<li class="nav-item"><a class="nav-link" href="#groupSubjects">Редактирование предметов группы</a></li>
				<li class="nav-item"><a class="nav-link" href="#accounts">Аккаунты</a></li>
			@endif
		</ul>
	</section>
	<section class="container-fluid groupEdit" id="groupEdit">
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
	@if ($group->exists)
		<section class="groupSubjects" id="groupSubjects">
			<!-- data-actionToDelete-->
			<form class="groupSubjects__subjects" action="{{ route('group.subject', ['group' => $group->id]) }}" method="post" name="subjectsPerGroupForm"  data-actionToDelete="{{ route('group.subject', ['group' => $group->id]) }}">
				<input type="hidden" name="_token" id="subjectsPerGroupToken" value="{{ csrf_token() }}"/>
				<div class="groupSubjects__table" id="subjectPerGroupTable">
					<div class="groupSubjects__table-item groupSubjects__table-item--heading">Предметы группы</div>
					@if ($group->subjects)
						@foreach ($group->subjects as $subject)
							<div class="groupSubjects__table-item" data-id="{{ $subject->id }}">
								<div class="{{ $subject->type->name }}"></div>
								<p class="name">{{ $subject->title }}</p>
								@if ($subject->tutors)
									<select class="select" name="subject">
										@foreach ($subject->tutors as $tutor)
											@if ($subjectTutor[$subject->id] == $tutor->user->id)
												<option selected="selected" value="{{ $tutor->user->id }}">{{ $tutor->shortFullname }}</option>
											@else
												<option value="{{ $tutor->user->id }}">{{ $tutor->shortFullname }}</option>
											@endif
										@endforeach
									</select>
								@endif
								<img class="groupSubjects__table-item-delete" src="/img/bin.svg" alt="delete"/>
							</div>
						@endforeach
					@endif
				</div>
			</form>
			<form class="groupSubjects__allSubjects" action="{{ route('group.tutors', ['group' => $group->id]) }}" method="post" name="allSubjectsForm">
				<input type="hidden" name="_token" id="allSubjectsToken" value="{{ csrf_token() }}"/>
				<div class="groupSubjects__table" id="allSubjectsTable">
					<div class="groupSubjects__table-item groupSubjects__table-item--heading">Все предметы</div>
					@if ($subjects->count())
						@foreach ($subjects as $subject)
							<div class="groupSubjects__table-item" data-id="{{ $subject->id }}">
								<div class="{{ $subject->type->name }}"></div>
								<p class="name name--allSubjects">{{ $subject->title }}</p>
								<img class="groupSubjects__table-item-add" src="/img/plusSign.svg" alt="add"/>
							</div>
						@endforeach					
					@endif
				</div>
			</form>
		</section>
		<section class="container accounts" id="accounts">
			<form action="{{ route('group.accounts', ['group' => $group->id]) }}" method="post" name="accounts">
				<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
				<h2 class="accounts__heading">Аккаунт старосты</h2>
				<select class="accounts__select" name="monitor">
					@if ($group->monitor)
						<option value="{{ $group->monitor->id }}" selected="selected" disabled="disabled">{{ $group->monitor->email }}</option>
					@else
						<option value="0" selected="selected" disabled="disabled"></option>
					@endif
					@if ($monitors)
						@foreach ($monitors as $user)
							<option value="{{ $user->id }}">{{ $user->email }}</option>
						@endforeach
					@endif
				</select>
				<h2 class="accounts__heading">Аккаунт группы</h2>
				<select class="accounts__select" name="user">
					@if ($group->user)
						<option value="{{ $group->user->id }}" selected="selected" disabled="disabled">{{ $group->user->email }}</option>
					@else
						<option value="0" selected="selected" disabled="disabled"></option>
					@endif
					@if ($groups)
						@foreach ($groups as $user)
							<option value="{{ $user->id }}">{{ $user->email }}</option>
						@endforeach
					@endif
				</select>
				<button class="accounts__submit" type="submit">Сохранить</button>
			</form>
		</section>
	@endif
@endsection