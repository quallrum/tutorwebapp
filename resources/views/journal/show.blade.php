@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="js/journal.js"></script>
@endsection

@section('content')
	<section class="container-fluid journal">
		<h2 class="journal__heading">{{ $group->title }} <span class="journal__heading-subject">{{ $subject->title }} ({{ $subject->type }})</span></h2>
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
			</div>
		</form>
	</section>
@endsection