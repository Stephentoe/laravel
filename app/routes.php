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

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('registration', 'UsersController@getRegistration');

Route::post('registration', array('before' => 'csrf',function()
{
    $rules = array(
        'email'    => 'required|email|unique:users',
        'password' => 'required|same:password_confirm',
        'name'     => 'required'
    );
    $validation = Validator::make(Input::all(), $rules);

    if ($validation->fails())
    {
        return Redirect::to('registration')->withErrors($validation)->withInput();
    }

    $user           = new User;
    $user->email    = Input::get('email');
    $user->password = Hash::make(Input::get('password'));
    $user->name     = Input::get('name');
    $user->admin    = Input::get('admin') ? 1 : 0;
    if ($user->save())
    {
        Auth::loginUsingId($user->id);
        return Redirect::to('profile');
    }
    return Redirect::to('registration')->withInput();
}));

Route::get('profile', 'UsersController@getProfile');

Route::get('login','UsersController@getLogin');

Route::post('login', 'UsersController@postLogin');

Route::get('secured', array('before' => 'auth', function()
{
    return 'This is a secured page!';
}));

Route::get('profile-edit', function()
{
    if (Auth::check())
    {
        $user = Input::old() ? (object) Input::old() :Auth::user();
        return View::make('profile_edit')->with('user',$user);
    }
});

Route::post('profile-edit', function()
{
    $rules = array(
        'email'    => 'required|email',
        'password' => 'same:password_confirm',
        'name'     => 'required'
    );
    $validation = Validator::make(Input::all(), $rules);

    if ($validation->fails())
    {
        return Redirect::to('profile-edit')->withErrors($validation)->withInput();
    }

    $user = User::find(Auth::user()->id);
    $user->email = Input::get('email');
    if (Input::get('password')) {
        $user->password = Hash::make(Input::get('password'));
    }
    $user->name = Input::get('name');
    if ($user->save())
    {
        return Redirect::to('profile')->with('notify','Information updated');
    }
    return Redirect::to('profile-edit')->withInput();
});

Route::get('restricted', array('before' => 'auth',
    function()
    {
        return 'This page is restricted to logged-in users!
        <a href="admin">Admins Click Here.</a>';
    }));

Route::get('admin', array('before' => 'auth_admin',function()
{
    return 'This page is restricted to Admins only!';
}));

Route::get('logout', function() {
    Auth::logout();
    return Redirect::to('login');
});