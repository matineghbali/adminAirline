<?php

namespace App\Http\Controllers;

use App\Passenger;
use App\Ticket;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

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
//        session()->forget('err');

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
        Alert::error('حذف شد!','');
        return back();
    }

    public function getTicket($id){
        $tickets=Ticket::wherePassenger_id($id)->latest()->paginate(1);
        if (count($tickets)==0){
            alert()->warning('هیچ بلیتی برای این مسافر وجود ندارد!' ,'');
            return redirect(route('getPassenger'));
        }
        return view('Passenger.tickets',['tickets'=>$tickets]);
    }



}
