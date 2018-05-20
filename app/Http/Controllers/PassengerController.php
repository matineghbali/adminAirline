<?php

namespace App\Http\Controllers;

use App\Passenger;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassengerController extends Controller
{
    public function getPassenger(){
        $passengers=Auth::user()->passengers()->latest()->paginate(5);
        return view('Passenger/all',['passengers' => $passengers]);
    }

    public function edit($id){
        $passenger=Passenger::find($id);
        return view('Passenger.edit',['passenger'=>$passenger]);
    }

    public function update(Request $request,$id){
        $passenger=Passenger::find($id);
        $passenger->update([
           'gender'=>$request['gender'],
            'fname'=>$request['fname'],
            'lname'=>$request['lname'],
            'doc_id'=>$request['doc_id'],
            'birthday'=>$request['birthday']
        ]);

        return redirect(route('getPassenger'));

    }

    public function Delete($id){
        Passenger::destroy($id);
        return back();
    }

    public function getTicket($id){
        $tickets=Ticket::wherePassenger_id($id)->latest()->paginate(1);
//        return $tickets[0]->passenger;
        return view('Passenger.tickets',['tickets'=>$tickets]);
    }



}
