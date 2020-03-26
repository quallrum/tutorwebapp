@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/chooseGroup.js"></script>
@endsection

@section('content')
	<section class="container chooseGroup">
		@include('shared.alerts')
		<div class="chooseGroup__window">
			<h2 class="chooseGroup__heading">Выберите группу</h2>
			@if ($groups and $groups->count())
				@foreach ($groups as $group)
					<label class="chooseGroup__item">
						<a class="chooseGroup__text" href="{{ route('journal.subject', ['group' => $group->id]) }}">{{ $group->title }}</a>
					</label>
				@endforeach
			@else
				You have no groups
			@endif
		</div>
	</section>
@endsection