<?php

namespace App\Http\Controllers;

use App\Flight;
use App\Passenger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReserveController extends AdminController
{
    //    start of reserve flight

    public function reservation()
    {


        $flight=Flight::find(session('flight_id'));
        $dateTime=explode('T',$flight['DepartureDateTime']);
        $time=explode(':',$dateTime[1]);
        return view('Panel/reservation',['data'=>$flight,'date'=>$dateTime[0],'time'=>$time[0].":".$time[1]]);
    }

    public function getPriceOfPassenger($type,$INFPrice,$CHDPrice,$ADTPrice){
        if ($type=='INF')
            $price=$INFPrice;
        elseif ($type=='CHD')
            $price=$CHDPrice;
        else
            $price=$ADTPrice;
        return $price;
    }

    public function reserve(Request $request){
        $flight=Flight::find(session('flight_id'));

//        $request=[
//            'birthday'
//            =>
//            "1374/2/20,1390/2/18,1396/2/21",
//            'customer_email'
//            =>
//            "parmin@gmail.com",
//            'customer_name'
//            =>
//            "mooooooooooh",
//            'customer_tel'
//            =>
//            "09367687112",
//            'fname'
//            =>
//            "matin,parmin,nozad",
//            'gender'
//            =>
//            "0,0,1",
//            'id'
//            =>
//            "431,444,789",
//            'lname'
//            =>
//            "eqbali,hastam,nikaa",
//            'numberOfPassengers'
//            =>
//            "3.1.1.1"
//        ];
        session(['customer'=>[
            'name'=>$request['customer_name'],
            'email'=>$request['customer_email'],
            'tel'=>$request['customer_tel'],
        ]]);
        $gender=explode(',' , $request['gender']);
        $fname=explode(',' , $request['fname']);
        $lname=explode(',' , $request['lname']);
        $doc_id=explode(',' , $request['id']);
        $birthday=explode(',' , $request['birthday']);

        $Number=explode('.',$request['numberOfPassengers']);

        $j=0;
        for ($i=0;$i<$Number[1];$i++){
            $type[$j++]='ADT';
        }
        for ($i=0;$i<$Number[2];$i++){
            $type[$j++]='CHD';
        }
        for ($i=0;$i<$Number[3];$i++){
            $type[$j++]='INF';
        }


        $NumberPassenger['passengerNumber']=$Number[0];
        $NumberPassenger['ADTNumber']=$Number[1];
        $NumberPassenger['CHDNumber']=$Number[2];
        $NumberPassenger['INFNumber']=$Number[3];


        //again request for search api,for get price of passengerType that may added in reservation page:)
        $response=$this->ApiForSearchFlight($flight['DepartureAirport'],$flight['ArrivalAirport'],$flight['DepartureDateTime'],
            $NumberPassenger['ADTNumber'],$NumberPassenger['CHDNumber'],$NumberPassenger['INFNumber']);


        //set price
        foreach ($response['PricedItineraries'][0]["AirItineraryPricingInfo"]["PTC_FareBreakdowns"] as $prices){
            $price[$prices["PassengerTypeQuantity"]['Code']]=
                [$prices["PassengerFare"]['TotalFare']['Amount']/$prices["PassengerTypeQuantity"]
                    ['Quantity'],$prices["PassengerTypeQuantity"]['Quantity']];
        }

        if (array_key_exists('ADT',$price)){
            $NumberPassenger['ADTPrice']=$price['ADT'][0];
        }
        else
            $NumberPassenger['ADTPrice']=0;
        if (array_key_exists('CHD',$price)){
            $NumberPassenger['CHDPrice']=$price['CHD'][0];
        }
        else
            $NumberPassenger['CHDPrice']=0;
        if (array_key_exists('INF',$price)){
            $NumberPassenger['INFPrice']=$price['INF'][0];
        }
        else
            $NumberPassenger['INFPrice']=0;

        /////////


        $flight->update([
            'user_id'=>Auth::user()->id,
            'passengerNumber'=>$Number[0],
            'ADTNumber'=>$Number[1],
            'CHDNumber'=>$Number[2],
            'INFNumber'=>$Number[3],
            'ADTPrice'=>$NumberPassenger['ADTPrice'],
            'CHDPrice'=>$NumberPassenger['CHDPrice'],
            'INFPrice'=>$NumberPassenger['INFPrice'],
            'price' => $response['PricedItineraries'][0]["AirItineraryPricingInfo"]["ItinTotalFare"]["TotalFare"]['Amount'],
        ]);

        $dateTime=explode('T',$flight['DepartureDateTime']);
        $time=explode(':',$dateTime[1]);
        $time=$time[0].":".$time[1];




//      add passengers in passenger table

        $count=count($gender);

        for ($i=0;$i<$count;$i++){
//            $passenger= Passenger::where('user_id',auth()->user()->id)->
//            where('doc_id',$doc_id[$i])->get();

            $passenger= Passenger::where('user_id',auth()->user()->id)->
            where(function ($q) use ($doc_id,$fname,$lname,$i) {
                $q->where('doc_id',$doc_id[$i])
                    ->orWhere(function ($query) use ($fname,$lname,$i){
                        $query->where('fname',$fname[$i])
                            ->where('lname',$lname[$i]);
                    });
            })->get();

            if (count($passenger)!= 0){

                Passenger::where('id',$passenger[0]['id'])->update([
                    'type'=> $type[$i],
                    'gender'=>$gender[$i],
                    'fname'=>$fname[$i],
                    'lname'=>$lname[$i],
                    'doc_id'=>$doc_id[$i],
                    'birthday'=>$birthday[$i],
                    'price'=> $this->getPriceOfPassenger($type[$i],$NumberPassenger['INFPrice'],$NumberPassenger['CHDPrice'],$NumberPassenger['ADTPrice']),
                    'reserve'=>1

                ]);
            }
            else{
                Passenger::create([
                    'user_id'=>auth()->user()->id,
                    'type'=>$type[$i],
                    'gender'=>$gender[$i],
                    'fname'=>$fname[$i],
                    'lname'=>$lname[$i],
                    'doc_id'=>$doc_id[$i],
                    'birthday'=>$birthday[$i],
                    'price'=> $this->getPriceOfPassenger($type[$i],$NumberPassenger['INFPrice'],$NumberPassenger['CHDPrice'],$NumberPassenger['ADTPrice']),
                    'reserve'=>1
                ]);
            }
        };

        $passenger= Passenger::where('user_id',auth()->user()->id)->where('reserve',1)->latest()->get();


        //info from table
        $html="<div class=\"row\">
                <div class=\"col-md-12\">
                    <div class=\"row\">
                        <div class=\"col-sm-12\" >
                        
                        
                            <div class=\"panelTitle\">اطلاعات بلیت ".CodeToCity($flight['DepartureAirport'])." به
                             ".CodeToCity($flight['ArrivalAirport'])." ". toPersianNum(jdate($dateTime[0])->format('%d %B، %Y'))."</div>


                            <div class=\"panel\">
                                <div class=\"row panelContent\" >
                                    <div class=\"col-md-3\">
                                        <h3>".$time."</h3>
                                        <span>".CodeToCity($flight['DepartureAirport']) . " " .CodeToCity($flight['ArrivalAirport'])."</span>
                                    </div>
                                    <div class=\"col-md-2\" style=\"padding-top: 20px\">
                                        <span class=\"text-muted\" >هواپیمایی ".$flight['MarketingAirlineFA']."</span>
                                    </div>
                                    <div class=\"col-md-2\">
                                        <ul>
                                            <li>هواپیما: <b>".$flight['AirEquipType']."</b></li>
                                            <li>شماره پرواز: <b>".toPersianNum($flight['FlightNumber'])."</b></li>
                                        </ul>
                                    </div>
                                    <div class=\"col-md-2\">
                                        <ul>
                                            <li>پرواز  <b>چارتر </b></li>
                                            <li>کلاس پروازی: <b>".$flight['cabinTypeFA']."</b></li>
                                        </ul>

                                    </div>
                                    <div class=\"col-md-3\">
                                        <h3>".toPersianNum($flight['passengerNumber'])." نفر </h3>
                                        <span>".toPersianNum($flight['price'])." تومان</span>
                                    </div>

                                </div>
                            </div>

                            <div class=\"passengerContent\" id=\"ADT\">
                                <div class=\"passengerHeader\">
                                    <h4 class=\"h4Passenger\">
                                        اطلاعات خریدار
                                    </h4>
                                </div>

                                <div class=\"passengerBody\">
                                    <div class=\"row passengerInfo \" style=\"padding: 10px\">
                                        <div class=\"col-sm-4\">
                                            <span>نام:</span>
                                            ". session('customer')['name']."
                                        </div>
                                        <div class=\"col-sm-4\">
                                            <span>ایمیل:</span>
                                            ". session('customer')['email']."
                                        </div>
                                        <div class=\"col-sm-4\">
                                            <span>شماره موبایل:</span>
                                            ".toPersianNum(session('customer')['tel'])."
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class=\"passengerContent\" style=\"margin-top: 30px\">
                                <div class=\"passengerHeader\">
                                    <h4 class=\"h4Passenger\">
                                        اطلاعات مسافران
                                    </h4>
                                </div>
                                <div class=\"passengerBody\" >
                                    <div class=\"table-responsive\">
                                        <table class=\"table table-striped table-hover\">
                                            <thead>
                                            <tr class=\"small\">
                                                <th>#</th>
                                                <th>نوع</th>
                                                <th>جنسیت</th>
                                                <th>نام و نام خانوادگی</th>
                                                <th>کد ملی</th>
                                                <th>تاریخ تولد</th>
                                                <th>قیمت بلیت</th>

                                            </tr>
                                            </thead>
                                            <tbody>";


        $j=0 ;
        for($i=0;$i<count($passenger );$i++){
            $html.="
                                                <tr>
                                                    <td>
                                                        ".toPersianNum(++$j)."
                                                    </td>
                                                    <td>";
            if($passenger[$i]['type']=='ADT')
                $html.='بزرگسال';
            elseif($passenger[$i]['type']=='CHD')
                $html.='کودک';
            else
                $html.='نوزاد';
            $html.="</td>
                                                    <td>";
            if($passenger[$i]['gender']==0)
                $html.="<b>خانم</b>";
            else
                $html.="<b>آقا</b>";
            $html.="</td>
                                                    <td>".$passenger[$i]['fname'] ." ". $passenger[$i]['lname']."
                                                    </td>
                                                    <td class=\"nowrap\">
                                                        <strong>
                                                            ".toPersianNum($passenger[$i]['doc_id'])."
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        ".toPersianNum($passenger[$i]['birthday'])."
                                                    </td>
                                                    <td>
                                                        ".toPersianNum($flight[$passenger[$i]['type'].'Price'])."
                                                    </td>

                                                </tr>";

        }//end foreach

        $html.="</tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>



                            <div class=\"row\" style=\"margin-top: 20px\">
                                <div class=\"col-sm-3\"></div>
                                <div class=\"col-sm-6\">
                                    <div class=\"row\">
                                        <div class=\"col-sm-6\">
                                            <div class=\"passengerBtn\">
                                                    <button class=\"btn btn-success btn-block\"  type=\"button\" id=\"editBtn\">اصلاح اطلاعات</button>
                                            </div>

                                        </div>
                                        <div class=\"col-sm-6\">
                                            <div class=\"passengerBtn\">
                                                <a  style=\"text-decoration: none;\" id=\"returnBtn\">
                                                    <button class=\"btn btn-primary btn-block\" type=\"button\" id=\"reserveBtn\" >رزرو بلیت</button>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        
        
        ";

        return $html;


    }


    public function reserved(){

        $passenger= Passenger::where('user_id',auth()->user()->id)->where('reserve',1)->latest()->get();
        $flight=Flight::find(session('flight_id'));

        $count=count($passenger);

        $TravelerInfo[]='';

        //receive passengers info
        for ($i=0;$i<$count;$i++) {
            $TravelerInfo[$i]=
                [
                    "PersonName"=> [
                        "NamePrefix"=> null,
                        "GivenName"=> $passenger[$i]['fname'],
                        "Surname"=> $passenger[$i]['lname']
                    ],
                    "Telephone"=> [
                        "CountryAccessCode"=> null,
                        "AreaCityCode"=> null,
                        "PhoneNumber"=> session('customer')['tel']
                    ],
                    "Email"=> [
                        "Value"=> session('customer')['email']
                    ],
                    "Document"=> [
                        "DocID"=> $passenger[$i]['doc_id'],
                        "DocType"=> 5,
                        "ExpireDate"=> "2020-03-27T13:51:40",
                        "DocIssueCountry"=> "IR",
                        "BirthCountry"=> null,
                        "DocHolderNationality"=> "IR"
                    ],
                    "Gender"=> 0,
                    "BirthDate"=> $this->DateFormatOfAPI($passenger[$i]['birthday']),
                    "PassengerTypeCode"=> $passenger[$i]['type'],
                    "AccompaniedByInfantInd"=> true
                ] ;
        }


        $now=Carbon::now();
        $now=str_replace(':','',$now);
        $now=str_replace(' ','',$now);
        $now=str_replace('-','',$now);
        $reference=Auth::user()->id .$now;


        $json =[
            "POS" => [
                "Source" => [
                    "RequestorID" => [
                        "MessagePassword" => "6eeb834b13420733904e2ae33b3d8821",
                        "Name" => "ghasedak"
                    ],
                    "Language" => null
                ]
            ],

            "AirItinerary" => [
                "OriginDestinationOptions" => [
                    [
                        "FlightSegment" => [
                            [
                                "DepartureAirport" => [
                                    "LocationCode" => $flight['DepartureAirport'],
                                    "Terminal" => null
                                ],
                                "ArrivalAirport" => [
                                    "LocationCode" => $flight['ArrivalAirport'],
                                    "Terminal" => null
                                ],
                                "Equipment" => null,
                                "DepartureDateTime" => $flight['DepartureDateTime'],
                                "ArrivalDateTime" => $flight['ArrivalDateTime'],
                                "StopQuantity" => null,
                                "RPH" => 0,
                                "MarketingAirline" => null,
                                "FlightNumber" => $flight['FlightNumber'],
                                "FareBasisCode" => $flight['FareBasisCode'],
                                "CabinType" => null,
                                "ResBookDesigCode" => null,
                                "Comment" => null,
                                "LockId" => null
                            ]
                        ]
                    ]
                ],
                "DirectionInd" => 0
            ],
            "PriceInfo" => null,

            "TravelerInfo" =>$TravelerInfo,

            "Fulfillment" => [
                "PaymentDetails" => [
                    [
                        "PaymentAmount" => [
                            "CurrencyCode" => "IRR",
                            "Amount" => $flight['price']
                        ]
                    ]
                ]
            ],
            "BookingReferenceID" => [
                "ID" => $reference
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://sepehrapitest.ir/api/OpenTravelAlliance/Air/AirBookV6",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response=json_decode($response,true);

        if ($response['Errors']!=null) {
            if ($response['Errors'][0]['Code'] == "AirBookValidationException"){
                if ($response['Errors'][0]['ShortText']=="flight departure date can not be earlier than today.")
                    $response='تاریخ پرواز نمی تواند قبل از امروز باشد!';
            }
            else{
                $response=$response['Errors'][0]['ShortText'];
            }
            $status='Error';
        }
        else{
            session(['ticketResponse' => $response]);
            $response=$response['AirReservation']['BookingReferenceID']['ID'];
            $status='Success';
        }
        return ['response' => $response,'status' => $status];
    }





//    end of reserve flight







}
