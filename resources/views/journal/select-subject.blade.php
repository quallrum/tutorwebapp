@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/chooseSubject.js"></script>
@endsection

@section('content')
	<section class="container-fluid chooseSubject">
		@include('shared.alerts')
		<div class="chooseSubject__window">
			<h2 class="chooseSubject__heading">Выберите предмет</h2>
			@if ($subjects and $subjects->count())
				<div class="chooseSubject__items">
					@foreach ($subjects as $subject)
						<a class="chooseSubject__item 
						@switch($subject->type)
							@case('Лек') lecture @break 
							@case('Лаб') laboratory @break 
							@case('Прак') practic @break 
						@endswitch
						" href="{{ route('journal.show', ['group' => $group->id, 'subject' => $subject->id]) }}">
							{{ $subject->title }}
						</a>
					@endforeach
				</div>
			@else
				<p class="chooseSubject__null">У этой группы пока нет предметов</p>
			@endif
			<a class="chooseGroup__back" href="{{ route('journal.group') }}">Назад</a>
		</div>
	</section>
@endsection