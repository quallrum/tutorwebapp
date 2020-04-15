@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="/js/alerts.js"></script>
	<script src="/js/groupEdit.js"></script>
@endsection

@section('content')
	{{-- @if ($monitors)
		<label for="group-title">Group monitor</label>
		<select name="monitor" id="group-monitor">
			@if ($group->monitor)
				<option>No monitor</option>
				<option disabled selected value="{{ $group->monitor->id }}">{{ $group->monitor->email }}</option>
			@else
				<option selected>No monitor</option>
			@endif
			@foreach ($monitors as $user)
				<option value="{{ $user->id }}">{{ $user->email }}</option>
			@endforeach
		</select>
		<br>
	@else
		No monitors found. Attach 'monitor' role to user<br>
	@endif --}}
	<section class="container-fluid groupEdit">
		@include('shared.alerts')
		<form action="{{ $action }}" method="post" name="groupEdit">
			@csrf
			@method($method)
			<div class="groupEdit__heading">
				<input class="groupEdit__group" type="text" name="title" value="{{ $group->title ?? '' }}"/>
			</div>
			@if ($group->students)
				<div class="groupEdit__table">
					<div class="groupEdit__table-item groupEdit__table-item--heading">ФИО</div>
					@foreach ($group->students as $student)
						<div class="groupEdit__table-item" data-id="{{ $student->id }}"> 
							<input class="name" type="text" name="students[{{ $student->id }}][lastname]" placeholder="Фамилия" value="{{ $student->lastname }}"/>
							<input class="name" type="text" name="students[{{ $student->id }}][firstname]" placeholder="Имя" value="{{ $student->firstname }}"/>
							<input class="name" type="text" name="students[{{ $student->id }}][fathername]" placeholder="Отчество" value="{{ $student->fathername }}"/>
							<div class="groupEdit__table-item-delete">&#8854;</div>
						</div>
					@endforeach
				</div>
			@endif
			
			<div class="groupEdit__addStudent" id="addStudentButton">Добавить студента</div>
			<button class="groupEdit__submit" type="submit">Сохранить</button>
		</form>
	</section>
@endsection