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
	@endif
	@if ($group->exists)
		<button type="submit">Save</button>
	@else
		<button type="submit">Create</button>
	@endif
</form>