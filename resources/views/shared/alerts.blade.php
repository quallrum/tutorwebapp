@if ($errors->any())
	<div class="alert alert--error" id="alertError" style="display: block;">
		@foreach ($errors->all() as $msg)
			<p class="alert__text alert__text--error" id="alertErrorText">{{ $msg }}</p>
		@endforeach
		<div class="alert__cross alert__cross--error" id="alertErrorCross">&#9587;</div>
	</div>
@else
	<div class="alert alert--error" id="alertError">
		<p class="alert__text alert__text--error" id="alertErrorText"></p>
		<div class="alert__cross alert__cross--error" id="alertErrorCross">&#9587;</div>
	</div>
@endif
@if (Session::has('success'))
	<div class="alert alert--success" id="alertSuccess" style="display: block;">
		<p class="alert__text alert__text--success" id="alertSuccessText">{{ Session::pull('success') }}</p>
		<div class="alert__cross alert__cross--success" id="alertSuccessCross">&#9587;</div>
	</div>
@else
	<div class="alert alert--success" id="alertSuccess">
		<p class="alert__text alert__text--success" id="alertSuccessText"></p>
		<div class="alert__cross alert__cross--success" id="alertSuccessCross">&#9587;</div>
	</div>
@endif