@extends('layouts.app')

@section('title', 'Оценки '.$group->title)

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/marks.js"></script>
@endsection

@section('content')
	<section class="container-fluid journal">
		<div class="journal__group">
			<h2 class="journal__group-heading">{{ $group->title }}</h2>
			@can('group.edit', $group)
				<a class="journal__group-button" href="{{ route('group.edit', ['group'=>$group->id]) }}">Изменить группу</a>
			@endcan
		</div>
		<div class="journal__subject">
			<h2 class="journal__subject-heading">{{ $subject->title }}</h2>
			<div class="{{ $subject->type->name }}"></div>
			{{-- <a class="journal__subject-button">Изменить предмет</a> --}}
		</div>
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="{{ route('journal.show', ['group' => $group->id, 'subject' => $subject->id]) }}">Посещаемость</a></li>
			<li class="nav-item"><a class="nav-link active">Оценки</a></li>
		</ul>
		@can('mark.edit')
			<form action="" method="post" name="marks" id="marks">
			@csrf
		@endcan
			<div class="journal__table">
				<div class="journal__table-line">
					<div class="journal__table-item">ФИО</div>					
					@if($user->can('mark.edit'))
						@foreach ($header as $column)
							<div class="journal__table-item journal__table-item--date" data-columnId="{{ $column->id }}" >
								<input class="header" type="text" name="header[{{ $column->id }}]" value="{{ $column->title }}">
								<div class="delete"><img src="/img/bin.svg" alt="del"/></div>
							</div>
						@endforeach
						<div class="journal__addColumn" id="marksAddColumn"><img src="/img/plusSign.svg" alt="add"/></div>
					@else
						@foreach ($header as $column)
							<div class="journal__table-item journal__table-item--date" data-columnId="{{ $column->id }}" >{{ $column->title }}</div>
						@endforeach
					@endif
				</div>
				@foreach ($group->students as $student)
					<div class="journal__table-line">
						<div class="journal__table-item" data-id="{{ $student->id }}">{{ $student->lastname }} {{ $student->firstname }}</div>
						@if($user->can('mark.edit'))
							@foreach ($table[$student->id] as $record)
								<div class="journal__table-item" data-itemId="{{ $record->id }}">
									<input class="absent" type="text" name="mark[{{ $record->id }}]" value="{{ $record->value }}"/>
								</div>
							@endforeach
						@else
							@foreach ($table[$student->id] as $record)
								<div class="journal__table-item">
									{{ $record->value }}
								</div>
							@endforeach
						@endif
					</div>
				@endforeach
			</div>
		@can('mark.edit')
			<div class="journal__buttons">
				<button class="journal__submit" type="submit">сохранить</button>
				<div class="journal__reload" id="reloadButton">отменить</div>
			</div>
			</form>
		@endcan
	</section>
@endsection