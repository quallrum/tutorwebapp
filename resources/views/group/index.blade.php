@if ($groups)
	@foreach ($groups as $group)
		<a href="{{ route('group.show', ['group' => $group->id]) }}">{{ $group->title }}</a>
		<a href="{{ route('group.edit', ['group' => $group->id]) }}">edit</a><br>
	@endforeach
@else
No groups found
@endif