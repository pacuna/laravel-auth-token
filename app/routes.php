<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

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

//para testear
// obtener token
// curl -X POST -d "email=test@mail.com&password=pass" http://localhost:8000/login
// curl -v -H "token: blabla" http://localhost:8000/secret

//si se trata curl -v http://localhost:8000/secret se obtiene 401
Route::get('/secret', function(){
	if(authenticated(Request::header('token'))){
		return Response::json(['content' => 'secret page']);
	}
	else{
		return Response::json(['content' => 'no estas autorizado'], 401);
	}
});

function authenticated($token){
	$user = User::where('authentication_token', '=', $token)->get();
	if($token && $user->count() > 0){
		return true;
	}
	else{
		return false;
	}
}
