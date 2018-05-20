<?php

namespace App\Http\Controllers;

use App\Passenger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReserveController extends AdminController
{
    //    start of reserve flight

    public function reservation()
    {
        return view('Panel/reservation',['data'=>session('data')]);
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

        $customer['name']=$request['customer_name'];
        $customer['email']=$request['customer_email'];
        $customer['tel']=$request['customer_tel'];

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


//        search flight again because may change the number of passengers
        $sessionArray=session('data');

        $sessionArray['passengerNumber']=$Number[0];
        $sessionArray['ADTNumber']=$Number[1];
        $sessionArray['CHDNumber']=$Number[2];
        $sessionArray['INFNumber']=$Number[3];



        $response=$this->ApiForSearchFlight($sessionArray['DepartureAirport'],$sessionArray['ArrivalAirport'],$sessionArray['DepartureDateTimeEN'],
            $sessionArray['ADTNumber'],$sessionArray['CHDNumber'],$sessionArray['INFNumber']);


        foreach ($response['PricedItineraries'][0]["AirItineraryPricingInfo"]["PTC_FareBreakdowns"] as $prices){
            $price[$prices["PassengerTypeQuantity"]['Code']]=
                [$prices["PassengerFare"]['TotalFare']['Amount']/$prices["PassengerTypeQuantity"]
                    ['Quantity'],$prices["PassengerTypeQuantity"]['Quantity']];

        }

        if (array_key_exists('ADT',$price)){
            $sessionArray['ADTPrice']=$price['ADT'][0];
        }
        else
            $sessionArray['ADTPrice']=0;
        if (array_key_exists('CHD',$price)){
            $sessionArray['CHDPrice']=$price['CHD'][0];
        }
        else
            $sessionArray['CHDPrice']=0;
        if (array_key_exists('INF',$price)){
            $sessionArray['INFPrice']=$price['INF'][0];
        }
        else
            $sessionArray['INFPrice']=0;

        $sessionArray['price']=$response['PricedItineraries'][0]["AirItineraryPricingInfo"]["ItinTotalFare"]["TotalFare"]['Amount'];




//      add passengers in passenger table

        $count=count($gender);

        for ($i=0;$i<$count;$i++){
            $passenger= Passenger::where('user_id',auth()->user()->id)->
            where('doc_id',$doc_id[$i])->get();



            if (count($passenger)!= 0){
                Passenger::where('id',$passenger[0]['id'])->update([
                    'type'=> $type[$i],
                    'gender'=>$gender[$i],
                    'fname'=>$fname[$i],
                    'lname'=>$lname[$i],
                    'doc_id'=>$doc_id[$i],
                    'birthday'=>$birthday[$i],
                    'price'=> $this->getPriceOfPassenger($type[$i],$sessionArray['INFPrice'],$sessionArray['CHDPrice'],$sessionArray['ADTPrice']),
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
                    'price'=> $this->getPriceOfPassenger($type[$i],$sessionArray['INFPrice'],$sessionArray['CHDPrice'],$sessionArray['ADTPrice']),
                    'reserve'=>1
                ]);
            }
        };

        $passenger= Passenger::where('user_id',auth()->user()->id)->where('reserve',1)->latest()->get();





        session(['dataForPayment' => ['data'=>$sessionArray,'passenger'=>$passenger,'customer'=>$customer] ]);
        session(['data'=>session('dataForPayment')['data']]) ;



        $html="<div class=\"row\">
                <div class=\"col-md-12\">
                    <div class=\"row\">
                        <div class=\"col-sm-12\" >
                        
                        
                            <div class=\"panelTitle\">اطلاعات بلیت ".CodeToCity($sessionArray['DepartureAirport'])." به
                             ".CodeToCity($sessionArray['ArrivalAirport'])." ". $sessionArray['DepartureDate']."</div>


                            <div class=\"panel\">
                                <div class=\"row panelContent\" >
                                    <div class=\"col-md-3\">
                                        <h3>".$sessionArray['DepartureTime']."</h3>
                                        <span>".CodeToCity($sessionArray['DepartureAirport']) . " " .CodeToCity($sessionArray['ArrivalAirport'])."</span>
                                    </div>
                                    <div class=\"col-md-2\" style=\"padding-top: 20px\">
                                        <span class=\"text-muted\" >هواپیمایی ".$sessionArray['MarketingAirlineFA']."</span>
                                    </div>
                                    <div class=\"col-md-2\">
                                        <ul>
                                            <li>هواپیما: <b>".$sessionArray['AirEquipType']."</b></li>
                                            <li>شماره پرواز: <b>".toPersianNum($sessionArray['FlightNumber'])."</b></li>
                                        </ul>
                                    </div>
                                    <div class=\"col-md-2\">
                                        <ul>
                                            <li>پرواز  <b>چارتر </b></li>
                                            <li>کلاس پروازی: <b>".$sessionArray['cabinTypeFA']."</b></li>
                                        </ul>

                                    </div>
                                    <div class=\"col-md-3\">
                                        <h3>".toPersianNum($sessionArray['passengerNumber'])." نفر </h3>
                                        <span>".toPersianNum($sessionArray['price'])." تومان</span>
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
                                            ".$customer['name']."
                                        </div>
                                        <div class=\"col-sm-4\">
                                            <span>ایمیل:</span>
                                            ".$customer['email']."
                                        </div>
                                        <div class=\"col-sm-4\">
                                            <span>شماره موبایل:</span>
                                            ".toPersianNum($customer['tel'])."
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
                                                        ".toPersianNum($sessionArray[$passenger[$i]['type'].'Price'])."
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
                                                    <button class=\"btn btn-primary btn-block\"  type=\"button\" id=\"editBtn\">اصلاح اطلاعات</button>
                                            </div>

                                        </div>
                                        <div class=\"col-sm-6\">
                                            <div class=\"passengerBtn\">
                                                    <button class=\"btn btn-primary btn-block\" type=\"button\" id=\"reserveBtn\">رزرو بلیت</button>
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

    public function unReserve(){
        $passengers=Auth::user()->passengers()->whereReserve(1)->get();
        foreach ($passengers as $passenger){
            $passenger->update([
                'reserve'=>0
            ]);

        }
    }

    public function reserved(){

        $count=count(session('dataForPayment')['passenger']);

        $TravelerInfo[]='';

        //receive passengers info
        for ($i=0;$i<$count;$i++) {
            $TravelerInfo[$i]=
                [
                    "PersonName"=> [
                        "NamePrefix"=> null,
                        "GivenName"=> session('dataForPayment')['passenger'][$i]['fname'],
                        "Surname"=> session('dataForPayment')['passenger'][$i]['lname']
                    ],
                    "Telephone"=> [
                        "CountryAccessCode"=> null,
                        "AreaCityCode"=> null,
                        "PhoneNumber"=> session('dataForPayment')['customer']['tel']
                    ],
                    "Email"=> [
                        "Value"=> session('dataForPayment')['customer']['email']
                    ],
                    "Document"=> [
                        "DocID"=> session('dataForPayment')['passenger'][$i]['doc_id'],
                        "DocType"=> 5,
                        "ExpireDate"=> "2020-03-27T13:51:40",
                        "DocIssueCountry"=> "IR",
                        "BirthCountry"=> null,
                        "DocHolderNationality"=> "IR"
                    ],
                    "Gender"=> 0,
                    "BirthDate"=> $this->DateFormatOfAPI(session('dataForPayment')['passenger'][$i]['birthday']),
                    "PassengerTypeCode"=> session('dataForPayment')['passenger'][$i]['type'],
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
                                    "LocationCode" => session('dataForPayment')['data']['DepartureAirport'],
                                    "Terminal" => null
                                ],
                                "ArrivalAirport" => [
                                    "LocationCode" => session('dataForPayment')['data']['ArrivalAirport'],
                                    "Terminal" => null
                                ],
                                "Equipment" => null,
                                "DepartureDateTime" => session('dataForPayment')['data']['DepartureDateTimeEN'],
                                "ArrivalDateTime" => session('dataForPayment')['data']['ArrivalDateTimeEN'],
                                "StopQuantity" => null,
                                "RPH" => 0,
                                "MarketingAirline" => null,
                                "FlightNumber" => session('dataForPayment')['data']['FlightNumber'],
                                "FareBasisCode" => session('dataForPayment')['data']['FareBasisCode'],
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
                            "Amount" => session('dataForPayment')['data']['price']
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
        $this->unReserve();
        return ['response' => $response,'status' => $status];
    }

//    end of reserve flight

}
