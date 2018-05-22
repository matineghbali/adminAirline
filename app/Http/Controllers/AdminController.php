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
        $passenger=$request['passenger'];
        $dateInput=$request['date'];
        $date=jdate()->format('%y/%m/%d');
        $array=explode('/',$date);
        $y='13'.$array[0];$m=$array[1];$d=$array[2];

        $infM=0;$infD=0;
        if ($passenger=='INF'){
            $infY=$y-2;
            $infM=$m;
            if ($d>7){
                $infD=$d-7;
                if ($infD<10)
                    $infD='0'.$infD;
            }
            else{
                $infM=$m-1;
                $infD=(daysOfMonth($m)+$d)-7;
            }
            $start=$infY.'/'.$infM.'/'.$infD;

            $end=$y.'/'.$infM.'/'.$infD;
        }


        else if ($passenger=='CHD'){
            $infM=$m;
            if ($d>7){
                $infD=$d-7;
                if ($infD<10)
                    $infD='0'.$infD;
            }
            else{
                $infM=$m-1;
                $infD=(daysOfMonth($m)+$d)-7;
            }

            $chdSY=$y-12;
            $start=$chdSY.'/'.$infM.'/'.($infD-1);
            $chdEY=$y-2;
            $end=$chdEY.'/'.$infM.'/'.($infD-1);
        }
        else {
            $infM=$m;
            if ($d>7){
                $infD=$d-7;
                if ($infD<10)
                    $infD='0'.$infD;
            }
            else{
                $infM=$m-1;
                $infD=(daysOfMonth($m)+$d)-7;
            }
            $adtSY='1300';
            $start=$adtSY.'/'.$infM.'/'.($infD-2);
            $adtEY=$y-12;
            $end=$adtEY.'/'.$infM.'/'.($infD-2);
        }

//        return ['start'=> $start,'end'=>$end];

        if ($dateInput<$start || $dateInput>$end)
            $status='invalid';
        else
            $status='valid';
        return ['status' => $status , 'start' => $start , 'end'=>$end , 'dateInput' => $dateInput , 'passenger' =>$passenger];
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









