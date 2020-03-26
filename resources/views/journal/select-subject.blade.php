@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/chooseSubject.js"></script>
@endsection

@section('content')
	<section class="container chooseSubject">
		@include('shared.alerts')
		<div class="chooseSubject__window">
			<h2 class="chooseSubject__heading">Выберите предмет</h2>
			@if ($subjects and $subjects->count())
				@foreach ($subjects as $subject)
					<label class="chooseSubject__item">
						<a class="chooseGroup__text" href="{{ route('journal.show', ['group' => $group->id, 'subject' => $subject->id]) }}">
							{{ $subject->title }} ({{ $subject->type }})
						</a>
					</label>
				@endforeach
			@else
				This group has no your subjects
			@endif
		</div>
	</section>
@endsection