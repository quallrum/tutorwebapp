@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/journal.js"></script>
@endsection

@section('content')
	<section class="container-fluid journal">
		@include('shared.alerts')
		<h2 class="journal__heading">{{ $group->title }} <span class="journal__heading-subject">{{ $subject->title }} ({{ $subject->type }})</span></h2>
		<div class="journal__addStudentPopUp">
			<h2 class="journal__addStudentPopUp-heading">Введите имя студента</h2>
			<input class="journal__addStudentPopUp-input" type="text" id="nameOfNewStudent"/>
			<button class="journal__addStudentPopUp-button" id="submitNewStudentName">Готово</button>
			<div class="journal__addStudentPopUp-cross" id="crossAddNewStudent">X</div>
		</div>
		{{-- @can('edit', 'App\Models\Journal') --}}
		@can('journal.edit')
			<div class="journal__addColumn" id="addColumn">Добавить колонку</div>
				<form action="" method="post" name="journal">
					@csrf
			@endcan
			<div class="journal__table">
				<div class="journal__table-line">
					<div class="journal__table-item">ФИО</div>
					@foreach ($header as $date)
						<div class="journal__table-item journal__table-item--date" style="overflow: hidden;">{{ $date }}</div>
					@endforeach
				</div>
				@foreach ($group->students as $student)
					<div class="journal__table-line">
						<div class="journal__table-item" data-id="{{ $student->id }}">{{ $student->lastname }} {{ $student->firstname }}</div>
						@if($user->can('journal.edit'))
							@foreach ($journal[$student->id] as $record)
								<div class="journal__table-item">
									@if ($record->editable())
										<input class="absent" type="text" name="journal[{{ $record->id }}]" value="{{ $record->value }}"/>
									@else
										{{ $record->value }}
									@endif
								</div>
							@endforeach
						@else
							@foreach ($journal[$student->id] as $record)
								<div class="journal__table-item">
									{{ $record->value }}
								</div>
							@endforeach
						@endif
					</div>
				@endforeach
			</div>
			@can('journal.edit')
				<div class="journal__buttons">
					<button class="journal__submit" type="submit">сохранить</button>
				</div>
				</form>
			@endcan
	</section>
@endsection