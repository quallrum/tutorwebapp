@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="js/journal.js"></script>
@endsection

@section('content')
	<header class="container-fluid">
		<div class="container header__content">
			<a href="/"><img class="header__logo" src="/img/logo.svg" alt="logo"/></a>
			<h1 class="header__heading">Tutor's WEB API</h1>
			<form class="journal__signOut" action="" method="POST" name="journalSignOut">
				<input type="hidden" name="_token" value=""/>
				<label>
					<img class="journal__signOut-img" src="/img/signOut.svg" alt="Sign Out"/>
					<input class="journal__signOut-submit" type="submit" value=""/>
				</label>
			</form>
		</div>
	</header>
	<section class="container-fluid journal">
		<h1 class="journal__heading">{{ $group->title }}</h1>
		<div class="journal__addStudentPopUp">
			<h2 class="journal__addStudentPopUp-heading">Введите имя студента</h2>
			<input class="journal__addStudentPopUp-input" type="text" id="nameOfNewStudent"/>
			<button class="journal__addStudentPopUp-button" id="submitNewStudentName">Готово</button>
			<div class="journal__addStudentPopUp-cross" id="crossAddNewStudent">X</div>
		</div>
		<div class="journal__addColumn" id="addColumn">Добавить колонку</div>
		<form action="" name="journal">
			<div class="journal__table">
				<div class="journal__table-line">
					<div class="journal__table-item">ФИО</div>
					@foreach ($header as $date)
						<div class="journal__table-item journal__table-item--date" style="overflow: hidden;">{{ $date }}</div>
					@endforeach
				</div>
				@foreach ($group->students as $student)
					<div class="journal__table-line">
						<div class="journal__table-item">{{ $student->lastname }} {{ $student->firstname }}</div>
						@foreach ($journal[$student->id] as $record)
							<div class="journal__table-item">
								@if ($record->editable())
									<input class="absent" type="text" name="" value="{{ $record->value }}"/>
								@else
									{{ $record->value }}
								@endif
							</div>
						@endforeach
					</div>
				@endforeach
			</div>
			<div class="journal__buttons">
				<button class="journal__submit" type="submit">сохранить</button>
				<div class="journal__addStudent" id="addStudent">Добавить студента</div>
			</div>
		</form>
	</section>
@endsection