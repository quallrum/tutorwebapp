@if ($errors->any())        
	<div class="authorization__alert" id="alert">
		@foreach ($errors->all() as $msg)
			<p class="authorization__alert-text" id="alertText">{{ $msg }}</p>
		@endforeach
		<div class="authorization__alert-cross" id="alertCross">X</div>
	</div>
@else
	<div class="authorization__alert" id="alert">
		<p class="authorization__alert-text" id="alertText"></p>
		<div class="authorization__alert-cross" id="alertCross">X</div>
	</div>
@endif
@if (Session::has('success'))        
	{{ Session::pull('success') }}<br>
@else

@endif