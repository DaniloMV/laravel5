<div style="width:100%;border: 1px solid red;">
	<div>
		{!! Form::open(array('url' => 'auth/login')) !!}
		{!! Form::text('email') !!}
		{!! Form::password('password') !!}
		{!! Form::submit('log me!') !!}
		{!! Form::close() !!}
	</div>
</div>