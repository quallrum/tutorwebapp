<form action="{{ $action }}" method="post">
	@csrf
	@method($method)
	@include('shared.errors')
	<label for="group-title">Group title</label>
	<input type="text" name="title" id="group-title" value="{{ $group->title ?? '' }}"><br>
	@if ($monitors)
		<label for="group-title">Group monitor</label>
		<select name="monitor" id="group-monitor">
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
	<button type="submit">Save</button>
</form>