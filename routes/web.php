<?php


use Carbon\Carbon;

Route::group(['middleware'=>'auth:web','prefix'=>'admin'],function (){
    $this->get('panel','AdminController@index')->name('adminPanel');

    $this->get('getFlight','AdminController@getFlight')->name('getFlight');
    $this->post('getFlight2','AdminController@getFlight2' )->name('getFlight2');
    $this->get('getFlight3','AdminController@getFlight3' )->name('getFlight3');

    $this->get('reservation','AdminController@reservation' )->name('reservation');
    $this->get('reservationBack','AdminController@reservationBack' )->name('reservationBack');
    $this->post('reserve','AdminController@reserve' )->name('reserve');
    $this->get('reserved','AdminController@reserved' )->name('reserved');

    $this->get('getBirthday/{passenger}','AdminController@getBirthday' )->name('getBirthday');

    $this->get('ticket','AdminController@ticket' )->name('ticket');



});


Route::group(['namespace' => 'Auth'] , function (){
    // Authentication Routes...
    $this->get('login', 'LoginController@showLoginForm')->name('login');
    $this->post('login', 'LoginController@login');
    $this->get('logout', 'LoginController@logout')->name('logout');

    // Login And Register With Google
    $this->get('login/google', 'LoginController@redirectToProvider');
    $this->get('login/google/callback', 'LoginController@handleProviderCallback');
    // Registration Routes...
    $this->get('register', 'RegisterController@showRegistrationForm')->name('register');
    $this->post('register', 'RegisterController@register');

    // Password Reset Routes...
    $this->get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    $this->post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('password/reset', 'ResetPasswordController@reset');
});


