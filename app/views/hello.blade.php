{{ Form::open(['url' => '/login'])}}
{{ Form::text('email')}}
{{ Form::password('password')}}
{{ Form::submit('Login')}}
{{ Form::close()}}
