<?php

namespace App\Http\Controllers;

use App\Flight;
use App\Ticket;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use TCPDF;
use TCPDF_FONTS;

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
//        return view('Panel/itckets',['tickets' => $tickets ]);
        return $this->ticket_generator($tickets);

    }

    public function ticket_generator($tickets)
    {
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Ghasedak-ict.com');
        $pdf->SetTitle('Ghasedak24 Ticket');
        $pdf->SetSubject('Ghasedak-ict.com');
        $pdf->SetKeywords('Ghasedak, PDF');
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 018', PDF_HEADER_STRING);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        /*
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        */
//        $pdf->SetMargins(5, 5, 5);
//        $pdf->SetAutoPageBreak(TRUE, 1);

//        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';
        $pdf->setLanguageArray($lg);

        $fontname = TCPDF_FONTS::addTTFfont(public_path().'/assets/fonts/IRANSansWeb.ttf', 'TrueTypeUnicode', '', 32);

        $pdf->SetFont($fontname, '', 10, '', false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//        $pdf->SetLineStyle(array('width' => 1, 'color' => array(0, 0, 0)));

//        $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
//        $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
//        $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
//        $pdf->Line(0, 0, 0, $pdf->getPageHeight());


        $html=view('Panel/tickets',['tickets' => $tickets ]);



        $pdf->AddPage();
        $pdf->WriteHTML($html, true, 0, true, 0);



        $pdf->Output('ticket.pdf', 'D');



    }
















//    public function getTicketPdf($id){
//        $pdf ='a';
//        $url ='http://ghasedak24.com:8089/getpdf?url=http://localhost:8000/admin/createTicketPdf/'.$id.'&token=123456YTREWQ!@';
//
//        $contextOptions = array(
//            "ssl" => array(
//                "verify_peer"      => false,
//                "verify_peer_name" => false,
//            ),
//        );
//        copy($url,$pdf,stream_context_create( $contextOptions ));
////        $name=Tour::where('id',$id)->pluck('tourName')->first();
//        return response()->download($pdf, 'tickets.pdf');
//    }
//
//
//    public function createTicketPdf($id){
//        $tickets=Ticket::where('BookingReference',$id)->get();
//
////        return view('Panel/tickets',['tickets' => $tickets ]);
//
//        return view('Panel/tickets', compact('tickets'));
//    }
//





}
