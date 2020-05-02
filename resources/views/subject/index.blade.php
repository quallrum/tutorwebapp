@extends('layouts.app')

@section('title', 'Предметы')

@section('head')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-grid.min.css"/>
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('content')
	<section class="container-fluid adminSubjects">
		<h1 class="adminSubjects__heading">Предметы</h1>
		<form class="adminSubjects__table" action="" name="adminSubjectsForm">
			@if ($subjects)
				@foreach ($subjects as $subject)
					<div class="adminSubjects__item">
						<p class="adminSubjects__name">{{ $subject->title }}</p>
						<div class="
							@switch($subject->type)
								@case('Лек') lecture @break 
								@case('Лаб') laboratory @break 
								@case('Прак') practic @break 
							@endswitch
						"></div>
						<a class="adminSubjects__edit" href="{{ route('subject.edit', ['subject' => $subject->id]) }}"><img src="/img/profileEdit.svg" alt="edit"/></a>
					</div>
				@endforeach
			@endif
		</form>
		<a class="adminSubjects__add" href="{{ route('subject.create') }}"><img src="img/plusSign.svg" alt="add"/></a>
	</section>
@endsection