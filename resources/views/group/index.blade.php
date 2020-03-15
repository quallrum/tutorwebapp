@if ($groups)
	@foreach ($groups as $group)
		<a href="{{ route('group.edit', ['group' => $group->id]) }}">{{ $group->title }}</a><br>
	@endforeach
@else
No groups found
@endif