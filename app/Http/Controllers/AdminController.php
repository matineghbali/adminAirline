<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('Panel/panel');

    }
    public function getFlight(){
        return view('Panel/flight');

    }

    public function getDate($date){
//        2018-04-17T00:00:00
//        2018-04-16T00:00:00
        if ($date==null)
        {
            $myarray=explode('-',Carbon::now());
            $day=explode(' ',$myarray[2]);
            $mydate="$myarray[0]-$myarray[1]-$day[0]T00:00:00";
            return $mydate;

        }
        else{
            $myarray=explode(' ',$date);
            switch ($myarray[1]){
                case 'Jan':
                    $month='01';
                    break;
                case 'Feb':
                    $month='02';
                    break;
                case 'Mar':
                    $month='03';
                    break;

                case 'Apr':
                    $month='04';
                    break;
                case 'May':
                    $month='05';
                    break;
                case 'Jun':
                    $month='06';
                    break;
                case 'Jul':
                    $month='07';
                    break;
                case 'Aug':
                    $month='08';
                    break;
                case 'Sep':
                    $month='09';
                    break;
                case 'Oct':
                    $month='10';
                    break;
                case 'Nov':
                    $month='11';
                    break;
                case 'Dec':
                    $month='12';
                    break;

            }
            $mydate="$myarray[3]-$month-$myarray[2]T00:00:00";
            return $mydate;


        }

    }

    public function getFlight2(Request $request){
//        $validation

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
                    "LocationCode"=> $request['OriginLocation']
                ],
                "DestinationLocation"=> [
                    "LocationCode"=> $request['DestinationLocation']
                ],
                "DepartureDateTime"=> [
                    "WindowBefore"=> 0,
                    "WindowAfter"=> 0,
                    "Value"=> $this->getDate($request['DepartureDateTime'])
                ]
            ],
            "TravelPreferences"=> null,
            "TravelerInfoSummary"=> [
                "AirTravelerAvail"=> [
                    "PassengerTypeQuantity"=> [
                        [
                            "Code"=> "ADT",
                            "Quantity"=> $request['adult']
                        ],
                        [
                            "Code"=> "CHD",
                            "Quantity"=> $request['child']
                        ],
                        [
                            "Code"=> "INF",
                            "Quantity"=> $request['baby']
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

        if ($err) {
            echo "cURL Error #:" ;
        } else {
            return json_decode($response,true);

        }




    }
}
