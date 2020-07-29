@extends('layouts.app')

@section('title', 'Журнал '.$group->title)

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/journal.js" type="module"></script>
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
			<li class="nav-item"><a class="nav-link active">Посещаемость</a></li>
			@if ($subject->hasMarks())
				<li class="nav-item"><a class="nav-link" href="{{ route('mark.show', ['group' => $group->id, 'subject' => $subject->id]) }}">Оценки</a></li>
			@endif
		</ul>
		@can('journal.edit')
			<form action="" method="post" name="journal">
			@csrf
		@endcan
			<div class="journal__table">
				<div class="journal__table-line">
					<div class="journal__table-item">ФИО</div>					
					@if($user->can('journal.edit'))
						@foreach ($header as $column)
							<div class="journal__table-item journal__table-item--date" data-columnId="{{ $column->id }}">
								{{ $column->date }}
								@if ($table[$group->students->first()->id]->first()->editable() or $user->role->name == 'admin')
									<div class="delete"><img src="/img/bin.svg" alt="del"/></div>
								@endif
							</div>
						@endforeach
						<div class="journal__addColumn" id="addColumn"><img src="/img/plusSign.svg" alt="add"/></div>
					@else
						@foreach ($header as $column)
							<div class="journal__table-item journal__table-item--date" data-columnId="{{ $column->id }}">{{ $column->date }}</div>
						@endforeach
					@endif
				</div>
				@foreach ($group->students as $student)
					<div class="journal__table-line">
						<div class="journal__table-item" data-id="{{ $student->id }}">{{ $student->lastname }} {{ $student->firstname }}</div>
						@if($user->can('journal.edit'))
							@foreach ($table[$student->id] as $record)
								<div class="journal__table-item" data-itemId="{{ $record->id }}">
									@if ($record->editable() or $user->role->name == 'admin')
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
				<div class="journal__reload" id="reloadButton">отменить</div>
				<a class="journal__download" href="{{ route('journal.file', ['group' => $group->id, 'subject' => $subject->id]) }}" download="download">скачать</a>
			</div>
			</form>
		@endcan
	</section>
@endsection