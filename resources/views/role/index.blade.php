@extends('layouts.app')

@section('title', 'Роли пользователей')

@section('head')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-grid.min.css"/>
	<link rel="stylesheet" href="/css/main.min.css"/>
@endsection

@section('scripts')
	<script src="js/alerts.js"></script>
	<script src="js/adminRoles.js"></script>
@endsection

@section('content')
	<section class="container-fluid adminRoles">
		<h1 class="adminRoles__heading">Роли пользователей</h1>
		<form action="{{ route('role.role') }}" method="post" name="adminRolesForm">
			<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}"/>
			<div class="adminRoles__table">
				@if ($users)
					@foreach ($users as $user)
						<div class="adminRoles__item" data-id="{{ $user->id }}">
							<p class="adminRoles__fullname">{{ $user->email }}</p>
							<select class="adminRoles__select" name="adminRolesRole">
								@foreach ($roles as $role)
									@if ($user->role_id == $role->id)
										<option value="{{ $role->id }}" selected="selected">{{ $role->title }}</option>
									@else
										<option value="{{ $role->id }}">{{ $role->title }}</option>
									@endif
								@endforeach
							</select>
						</div>
					@endforeach
				@endif
			</div>
		</form>
	</section>
@endsection