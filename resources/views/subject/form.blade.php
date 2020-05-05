@extends('layouts.app')

@section('title', $subject->exists ? 'Редактирование предмета '.$subject->title : 'Создание предмета')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/alerts.js"></script>
	<script src="/js/adminEditSubject.js"></script>
@endsection

@section('content')
	<section class="container-fluid adminEditSubject">
		<form action="{{ $action }}" name="adminEditSubjectNameType">
			<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			@method($method)
			<div class="adminEditSubject__heading">
				<input class="adminEditSubject__group" type="text" name="adminEditSubjectGroup" value="{{ $subject->title }}" autofocus="autofocus"/>
				<select class="adminEditSubject__type" name="type" id="adminEditSubjectType">
					@foreach ($types as $type)
						@if ($subject->type->id == $type->id)
							<option value="{{ $type->id }}" selected="selected">{{ $type->title }}</option>
						@else
							<option value="{{ $type->id }}">{{ $type->title }}</option>
						@endif
					@endforeach
				</select>
				<button class="adminEditSubject__submit" type="submit">Сохранить</button>
			</div>
		</form>
		<form class="adminEditSubject__form" action="{{ route('subject.tutor', ["subject" => $subject->id]) }}" name="adminEditSubject">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" id="adminEditSubjectToken"/>
			@csrf
			<div class="adminEditSubject__table adminEditSubject__table--forSubject">
				<div class="adminEditSubject__table-item adminEditSubject__table-item--heading">Преподаватели этого предмета</div>
				@if ($subject->tutors)
					@foreach ($subject->tutors as $tutor)
						<div class="adminEditSubject__table-item" data-id="{{ $tutor->user->id }}">
							<p class="name">{{ $tutor->fullname }}</p>
							<p class="email">{{ $tutor->user->email }}</p>
							<img class="adminEditSubject__table-item-delete" src="/img/bin.svg" alt="delete"/>
						</div>
					@endforeach
				@endif
			</div>
			<div class="adminEditSubject__tutorsForm">
				<div class="adminEditSubject__table adminEditSubject__table--all">
					<div class="adminEditSubject__table-item adminEditSubject__table-item--heading">Все преподаватели</div>
					@if ($allTutors)
						@foreach ($allTutors as $tutor)
							<div class="adminEditSubject__table-item" data-id="{{ $tutor->user->id }}"> 
								<p class="name">{{ $tutor->fullname }}</p>
								<p class="email">{{ $tutor->user->email }}</p>
								<img class="adminEditSubject__addTutor" src="/img/plusSign.svg" alt="add"/>
							</div>
						@endforeach
					@endif
				</div>
			</div>
		</form>
	</section>
@endsection