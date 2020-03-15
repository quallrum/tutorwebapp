<form action="{{ $action }}" method="post">
	@csrf
	@method($method)
	@include('shared.alerts')
	<label for="group-title">Group title</label>
	<input type="text" name="title" id="group-title" value="{{ $group->title ?? '' }}"><br>
	@if ($monitors)
		<label for="group-title">Group monitor</label>
		<select name="monitor" id="group-monitor">
			@if ($group->monitor)
				<option>No monitor</option>
			@else
				<option selected>No monitor</option>
			@endif
			@foreach ($monitors as $user)
				@if ($group->monitor_id == $user->id)
					<option selected value="{{ $user->id }}">{{ $user->email }}</option>
				@else
					<option value="{{ $user->id }}">{{ $user->email }}</option>
				@endif
			@endforeach
		</select>
		<br>
	@else
		No monitors found. Attach 'monitor' role to user<br>
	@endif
	@if ($group->exists)
		<button type="submit">Save</button>
	@else
		<button type="submit">Create</button>
	@endif
</form>