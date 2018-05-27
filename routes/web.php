<?php


use Carbon\Carbon;

Route::group(['middleware'=>'auth:web','prefix'=>'admin'],function (){
    $this->get('panel','AdminController@index')->name('adminPanel');

    $this->get('getFlight','FlightController@getFlight')->name('getFlight');
    $this->post('getFlight2','FlightController@getFlight2' )->name('getFlight2');
    $this->get('getFlight3','FlightController@getFlight3' )->name('getFlight3');

    $this->get('reservation','ReserveController@reservation' )->name('reservation');
    $this->post('reserve','ReserveController@reserve' )->name('reserve');
    $this->get('unReserve','ReserveController@unReserve' )->name('unReserve');
    $this->get('reserved','ReserveController@reserved' )->name('reserved');

    $this->post('getBirthday','AdminController@getBirthday' )->name('getBirthday');

    $this->get('ticket','TicketController@ticket' )->name('ticket');
    $this->get('tickets','TicketController@tickets' )->name('tickets');



    $this->get('getPassenger','PassengerController@getPassenger' )->name('getPassenger');
    $this->get('passengers/{id}/edit','PassengerController@Edit' )->name('EditPassenger');
    $this->patch('passenger/{id}','PassengerController@Update' )->name('UpdatePassenger');
    $this->DELETE('passenger/{id}','PassengerController@Delete' )->name('DeletePassenger');
    $this->get('getTicket/{id}','PassengerController@getTicket' )->name('getTicket');
    $this->get('pastPassenger','PassengerController@pastPassenger' )->name('pastPassenger');
    $this->get('isPastPassenger','PassengerController@isPastPassenger' )->name('isPastPassenger');

});

Route::get('/',function (){
    return 'HOME PAGE';
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


