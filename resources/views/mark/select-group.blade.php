@extends('layouts.app')

@section('title', 'Выберите группу')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/chooseGroup.js" type="module"></script>
@endsection

@section('content')
	<section class="container-fluid chooseGroup">
		<div class="chooseGroup__window">
			<h2 class="chooseGroup__heading">Выберите группу</h2>
			@if ($groups and $groups->count())
				<div class="chooseGroup__items">
					@foreach ($groups as $group)
						<a class="chooseGroup__item" href="{{ route('mark.subject', ['group' => $group->id]) }}">{{ $group->title }}</a>
					@endforeach
				</div>
			@else
				<p class="chooseGroup__null">У вас пока нету групп</p>
			@endif
			<a class="chooseGroup__back" href="{{ route('home') }}">Назад</a>
		</div>
	</section>
@endsection