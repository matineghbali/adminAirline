<?php

namespace App\Http\Controllers;

use App\Flight;
use App\Ticket;
use Illuminate\Http\Request;

class TicketController extends AdminController
{

    public function ticket(){

        $ticket=[
            'flightInfo'=>session('dataForPayment')['data'],
            'passenger'=>session('dataForPayment')['passenger'],
            'customer'=>session('dataForPayment')['customer'],
            'ticketInfo' => session('ticketResponse')
        ];

        $flight=Flight::create([
            'DepartureAirport'=>$ticket['flightInfo']['DepartureAirport'],
            'ArrivalAirport'=>$ticket['flightInfo']['ArrivalAirport'],
            'DepartureDateTime'=>$ticket['flightInfo']['DepartureDateTimeEN'],
            'ArrivalDateTime'=>$ticket['flightInfo']['ArrivalDateTimeEN'],
            'AvailableSeatQuantity'=>$ticket['flightInfo']['AvailableSeatQuantity'],
            'FlightNumber'=>$ticket['flightInfo']['FlightNumber'],
            'FareBasisCode'=>$ticket['flightInfo']['FareBasisCode'],
            'MarketingAirline'=>$ticket['flightInfo']['MarketingAirlineEN'],
            'cabinType'=>$ticket['flightInfo']['cabinTypeEN'],
            'AirEquipType'=>$ticket['flightInfo']['AirEquipType'],
            'passengerNumber'=>$ticket['flightInfo']['passengerNumber'],
            'price'=>$ticket['flightInfo']['price'],
            'ADTPrice'=>$ticket['flightInfo']['ADTPrice'],
            'CHDPrice'=>$ticket['flightInfo']['CHDPrice'],
            'INFPrice'=>$ticket['flightInfo']['INFPrice'],
        ]);



        $count=count($ticket['passenger']);

        for ($i=0;$i<$count;$i++){
            Ticket::create([
                'passenger_id' => $ticket['passenger'][$i]['id'],
                'flight_id' => $flight['id'],
                'ticketNumber' => $ticket['ticketInfo']['AirReservation']['Ticketing'][$i]['TicketDocumentNbr'],
                'dateBook' => $ticket['ticketInfo']['AirReservation']['DateBooked'],
                'BookingReference' => $ticket['ticketInfo']['AirReservation']['BookingReferenceID']['ID'],
                'customer_name'=>$ticket['customer']['name'],
                'customer_email'=>$ticket['customer']['email'],
                'customer_tel'=>$ticket['customer']['tel'],

            ]);
        }

        session(['ticket_id' => $ticket['ticketInfo']['AirReservation']['BookingReferenceID']['ID']]);
        return redirect(route('tickets'));

    }

    public function tickets(){
        $tickets=Ticket::where('BookingReference',session('ticket_id'))->get();
        return view('Panel/tickets',['tickets' => $tickets ]);
    }


}
