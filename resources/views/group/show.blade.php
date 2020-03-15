<strong>Title:</strong> {{ $group->title }}<br>
<strong>Monitor:</strong> {{ $group->monitor->email }}<br>
<br>
@foreach ($group->students as $student)
	{{ $student->fullname }}<br>
@endforeach