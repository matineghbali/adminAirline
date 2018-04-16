<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('Panel/panel');

    }
    public function getFlight(){
        return view('Panel/flight');

    }
    public function getFlight2(Request $request){
//        $validation
        // "2018-04-16T00:00:00"
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
                    "Value"=> $request['DepartureDateTime']
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
            $res=json_decode($response,true);
        }
        return view('Panel.flight2',compact('res'));

    }
}
