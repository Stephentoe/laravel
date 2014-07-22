<?php

class UsersController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function getLogin() {
        return View::make('login');
    }
    public function postLogin() {
        $user = array(
            'email' => Input::get('email'),
            'password' => Input::get('password')
        );

        if (Auth::attempt($user))
        {
            return Redirect::to('profile');
        }

        return Redirect::to('login')->with('login_error','Could not log in.');
    }
    public function getRegistration() {
        return View::make('registration');
    }
    public function postRegistration() {

    }
    public function getProfile() {
        if (Auth::check())
        {
            return View::make('profile')->with('user',Auth::user());
        }
        else
        {
            return Redirect::to('login')->with('login_error','You must login first.');
        }
    }
    











}