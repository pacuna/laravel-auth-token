<?php


//for testing purposes
Route::get('/loginform', function(){
	return View::make('hello');
			});

Route::post('/login', function(){
	$email = Input::get('email');
	$password = Input::get('password');
	if (Auth::attempt(array('email' => $email, 'password' => $password)))
	{
		//loop for create a unique token
		while(true){
			$token = str_random(15); //TODO: search for a secure way
			$user = User::where('authentication_token', '=', $token)->get();
			if($user->count() > 0) continue;
			else break;
		}

		//set the token for the user
		Auth::user()->update(['authentication_token' => $token]);
		return Response::json(['auth_token' => $token]);
	}

	else{
		return Response::json(['content' => 'datos incorrectos'], 401);
	}
});

Route::get('/', function(){
	return Response::json(['content' => 'main page']);
});

// for testing
// get token:
// curl -X POST -d "email=test@mail.com&password=pass" http://localhost:8000/login
// send token:
// curl -v -H "token: blahblah" http://localhost:8000/secret

//trying to access without token -v http://localhost:8000/secret returns 401
Route::get('/secret', function(){
	if(authenticated(Request::header('token'))){
		return Response::json(['content' => 'secret page']);
	}
	else{
		return Response::json(['content' => 'no estas autorizado'], 401);
	}
});

//TODO: move this function to proper place
function authenticated($token){
	$user = User::where('authentication_token', '=', $token)->get();
	if($token && $user->count() > 0){
		return true;
	}
	else{
		return false;
	}
}
