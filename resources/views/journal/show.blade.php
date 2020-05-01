@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/journal.js"></script>
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
			<div class="
				@switch($subject->type)
					@case('Лек') lecture @break 
					@case('Лаб') laboratory @break 
					@case('Прак') practic @break 
				@endswitch
			"></div>
			{{-- <a class="journal__subject-button">Изменить предмет</a> --}}
		</div>
		@can('journal.edit')
			<form action="" method="post" name="journal">
			@csrf
		@endcan
			<div class="journal__table">
				<div class="journal__table-line">
					<div class="journal__table-item">ФИО</div>					
					@if($user->can('journal.edit'))
						@foreach ($header as $date)
							<div class="journal__table-item journal__table-item--date" style="overflow: hidden;">
								{{ $date }}
								@if ($journal[$group->students->first()->id]->first()->editable())
									<img src="/img/bin.svg" alt="del" class="delete">
								@endif
							</div>
						@endforeach
						<div class="journal__addColumn" id="addColumn"><img src="/img/plusSign.svg" alt="add"/></div>
					@else
						@foreach ($header as $date)
							<div class="journal__table-item journal__table-item--date" style="overflow: hidden;">{{ $date }}</div>
						@endforeach
					@endif
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
				<div class="journal__reload" id="reloadButton">отменить</div>
			</div>
			</form>
		@endcan
	</section>
@endsection