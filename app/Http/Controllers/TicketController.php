<?php

namespace App\Http\Controllers;

use App\Flight;
use App\Passenger;
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

        $passenger= Passenger::where('user_id',auth()->user()->id)->where('reserve',1)->latest()->get();

//        $flight=Flight::find(session('flight_id'));

        $ticket=[
            'ticketInfo' => session('ticketResponse')
        ];

        $count=session('passengerCount');

        for ($i=0;$i<$count;$i++){
            Ticket::create([
                'passenger_id' => $passenger[$i]['id'],
                'flight_id' => session('flight_id'),
                'ticketNumber' => $ticket['ticketInfo']['AirReservation']['Ticketing'][$i]['TicketDocumentNbr'],
                'dateBook' => $ticket['ticketInfo']['AirReservation']['DateBooked'],
                'BookingReference' => $ticket['ticketInfo']['AirReservation']['BookingReferenceID']['ID'],
                'customer_name'=>session('customer')['name'],
                'customer_email'=>session('customer')['email'],
                'customer_tel'=>session('customer')['tel'],

            ]);
        }

        $this->unReserve();

        session(['ticket_id' => $ticket['ticketInfo']['AirReservation']['BookingReferenceID']['ID']]);

//        session()->forget('flight_id');

        return redirect(route('tickets'));



    }

    public function tickets(){
        $tickets=Ticket::where('BookingReference',session('ticket_id'))->get();
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
