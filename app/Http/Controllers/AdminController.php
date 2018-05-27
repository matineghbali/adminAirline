<?php
namespace App\Http\Controllers;

require_once __DIR__ . '/../Function/funnction.php';

use App\Flight;
use App\Passenger;
use App\Ticket;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function MongoDB\BSON\toJSON;
use Morilog\Jalali\jDate;
use Morilog\Jalali\jDateTime;
use RealRashid\SweetAlert\Facades\Alert;
class AdminController extends Controller
{
    public $date="false";

//    functions

    public function DateFormatOfAPI($date){
        //input:1397/1/21
        //input for api :"2018-04-16T00:00:00"
        //output of carbon : 2018-04-18 10:20:30


        $myarray = explode('/', $date);

        $miladi = jalali_to_gregorian($myarray[0], $myarray[1], $myarray[2], 0);
        if ($miladi[1]<10)
            $miladi[1]="0$miladi[1]";
        if ($miladi[2]<10)
            $miladi[2]="0$miladi[2]";


        $myDate = "$miladi[0]-$miladi[1]-$miladi[2]T00:00:00";
        return $myDate;
    }

    public  function ApiForSearchFlight($OriginLocation,$DestinationLocation,$DepartureDateTime,$ADT,$CHD,$INF){
        $json=[
            "POS"=> [
                "Source"=> [
                    "RequestorID"=> [
                        "MessagePassword"=> "6eeb834b13420733904e2ae33b3d8821",
                        "Name"=> "ghasedak"
                    ],
                    "Language"=> null
                ]
            ],
            "OriginDestinationInformation"=> [
                "OriginLocation"=> [
                    "LocationCode"=> $OriginLocation
                ],
                "DestinationLocation"=> [
                    "LocationCode"=> $DestinationLocation
                ],
                "DepartureDateTime"=> [
                    "WindowBefore"=> 0,
                    "WindowAfter"=> 0,
                    "Value"=> $DepartureDateTime
                ]
            ],
            "TravelPreferences"=> null,
            "TravelerInfoSummary"=> [
                "AirTravelerAvail"=> [
                    "PassengerTypeQuantity"=> [
                        [
                            "Code"=> "ADT",
                            "Quantity"=> $ADT
                        ],
                        [
                            "Code"=> "CHD",
                            "Quantity"=> $CHD
                        ],
                        [
                            "Code"=> "INF",
                            "Quantity"=> $INF
                        ]
                    ]
                ]
            ]
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://sepehrapitest.ir/api/OpenTravelAlliance/Air/AirLowFareSearchV6",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($json),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response=json_decode($response,true);
        return $response;
    }

    public function getBirthday(Request $request){
        $DepartureDate=session('data')['DepartureDateTimeEN'];
        $passenger=$request['passenger'];
        $dateInput=$request['date'];
        $dateInput=str_replace('/','-',$dateInput);
        $explodeDateInput=explode('-',$dateInput);
        for($i=0;$i<count($explodeDateInput);$i++){
            if ($explodeDateInput[$i]<10)
                $explodeDateInput[$i]='0'.$explodeDateInput[$i];
        }

        $dateInput=implode('-',$explodeDateInput);

        $ArrayDepartureDate=explode('T',$DepartureDate);


        if ($passenger=='INF'){
            $start= jDate::forge($ArrayDepartureDate[0])->reforge('- 2 Years')->format('date');
            $end  = jDate::forge($ArrayDepartureDate[0])->reforge('- 7 Days')->format('date');
        }
        elseif ($passenger=='CHD'){
            $start= jDate::forge($ArrayDepartureDate[0])->reforge('- 1 Days - 12 Years')->format('date');
            $end  = jDate::forge($ArrayDepartureDate[0])->reforge('+ 2 Days - 2 Years')->format('date');
        }
        elseif ($passenger=='ADT'){
            $start= jDate::forge('1921-03-21')->format('date');
            $end  = jDate::forge($ArrayDepartureDate[0])->reforge('- 2 Days - 12 Years')->format('date');
        }



        if ($dateInput<$start || $dateInput>$end)
            $status='invalid';
        else
            $status='valid';

        return ['status' => $status , 'start' => $start , 'end'=>$end , 'dateInput' => $dateInput ,
            'DepartureDate' => jdate($ArrayDepartureDate[0])->format('date') , 'passenger' =>$passenger];
    }


    public static function getMarketingAirlineFA($MarketingAirlineEN){
        if ($MarketingAirlineEN=="QESHM AIR")
            return 'قشم ایر';
        else if ($MarketingAirlineEN=="MERAJ")
            return 'معراج';
        else if ($MarketingAirlineEN=="TABAN")
            return 'تابان ایر';
        else if ($MarketingAirlineEN=="ZAGROS")
            return 'زاگرس';
        else
            return $MarketingAirlineEN;
    }

    public static function getCabinTypeFA($cabinTypeEN){
        if ($cabinTypeEN=="Economy")
            return 'اکونومی';
        else
            return $cabinTypeEN;

    }

//    end functions

    public function index(){
        return view('Panel/panel');

    }


}









