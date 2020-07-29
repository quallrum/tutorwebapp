@extends('layouts.app')

@section('title', 'Выберите предмет')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/chooseSubject.js" type="module"></script>
@endsection

@section('content')
	<section class="container-fluid chooseSubject">
		<div class="chooseSubject__window">
			<h2 class="chooseSubject__heading">Выберите предмет</h2>
			@if ($subjects and $subjects->count())
				<div class="chooseSubject__items">
					@foreach ($subjects as $subject)
						<a class="chooseSubject__item {{ $subject->type->name }}" href="{{ route('mark.show', ['group' => $group->id, 'subject' => $subject->id]) }}">
							{{ $subject->title }}
						</a>
					@endforeach
				</div>
			@else
				<p class="chooseSubject__null">У этой группы пока нет предметов</p>
			@endif
			<a class="chooseGroup__back" href="@can('mark.changeGroup') {{route('mark.group') }} @else {{ route('home') }} @endcan">Назад</a>
		</div>
	</section>
@endsection