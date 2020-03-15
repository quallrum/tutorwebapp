<strong>Title:</strong> {{ $group->title }}<br>
<strong>Monitor:</strong> @if ($group->monitor) {{ $group->monitor->email }} @else No monitor @endif<br>

<br>
@foreach ($group->students as $student)
	{{ $student->fullname }}<br>
@endforeach