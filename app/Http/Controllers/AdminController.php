<?php
namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function MongoDB\BSON\toJSON;

class AdminController extends Controller
{
    public $date="false";

    public function index(){
        return view('Panel/panel');

    }
    public function getFlight(){
        return view('Panel/flight');

    }

    public function getDate($date){
        //input:1397/1/21
        //input for api :"2018-04-16T00:00:00"
        //output of carbon : 2018-04-18 10:20:30


        $myarray = explode('/', $date);

        $miladi = jalali_to_gregorian($myarray[0], $myarray[1], $myarray[2], 0);
        if ($miladi[1]<10)
            $miladi[1]="0$miladi[1]";
        if ($miladi[2]<10)
            $miladi[2]="0$miladi[2]";


        $myDate = "$miladi[0]-$miladi[1]-$miladi[2]";
        $carbon=explode(' ',Carbon::now());

        if ($myDate < $carbon[0]){
            $myDateForApi = "$carbon[0]T00:00:00";
            $carbon=explode('-',$carbon[0]);
            session(['Date'=>gregorian_to_jalali($carbon[0],$carbon[1],$carbon[2],1)]);
//            $this->date=gregorian_to_jalali($carbon[0],$carbon[1],$carbon[2],1);

        }
        else{
            $myDateForApi = $myDate.'T00:00:00';
            session(['Date'=>'false']);

//            $this->date="false";
        }

        return $myDateForApi;

    }

    public function getFlight2(Request $request){

        session()->forget('Errors');
        session()->forget('PassengerNumERR');
        session()->forget('Response');


        //$validation
        if ($request['OriginLocation']=='null')
            $request['OriginLocation']='';
        if ($request['DestinationLocation']=='null')
            $request['DestinationLocation']='';
        $validation=Validator::make($request->all(),[
            'DepartureDateTime' => 'required',
            'OriginLocation' => 'required',
            'DestinationLocation' => 'required'
        ]);
        if ($validation->fails())
            session(['Errors'=>$validation->errors()]);
        elseif (($request['INF']+$request['ADT']+$request['CHD'])>9)
            session(['PassengerNumERR'=>"تعداد مسافرها نمی تواند بیشتر از 9 باشد، درصورت نیاز تعداد بیشتر جداگانه صادر کنید"]);

        elseif ($request['INF']>$request['ADT'])
            session(['PassengerNumERR'=>"تعداد نوزاد نمی تواند بیشتر از بزرگسال باشد"]);

        else{
            $response=$this->ApiForSearchFlight($request['OriginLocation'],$request['DestinationLocation'],$this->getDate($request['DepartureDateTime']),
            $request['ADT'],$request['CHD'],$request['INF']);

            session(['Response'=>['response'=>$response,'date'=> session('Date')]]);


        }
    }
    public function ApiForSearchFlight($OriginLocation,$DestinationLocation,$DepartureDateTime,$ADT,$CHD,$INF){
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


    public function getFlight3(){

        // ارورهای ولیدیشن
        $html='';
        if (session()->has('Errors'))       //error haye validation
        {
            $error=session('Errors');
            if ($error->first('DepartureDateTime'))
                $html.="<div class='row'><div class=\"btn btn-danger disabled col-sm-6\" >".
                    $error->first('DepartureDateTime'). '</div></div><br>';
            if ($error->first('OriginLocation'))
                $html.="<div class='row'><div class=\"btn btn-danger disabled col-sm-6\" >".
                    $error->first('OriginLocation'). '</div></div><br>';
            if ($error->first('DestinationLocation'))
                $html.="<div class='row'><div class=\"btn btn-danger disabled col-sm-6\" >".
                    $error->first('DestinationLocation'). '</div></div><br>';

        }

        //اارور های تعداد مسافران
        elseif (session()->has('PassengerNumERR')){
            $error=session('PassengerNumERR');
            $html.="<div class='row'><div class=\"btn btn-danger disabled col-sm-6\" >".
                $error. '</div></div><br>';


        }
         //ارورهای سرور
        else{
            $myRes=session('Response');

            if (session('Date') == false)
                session(['Date'=>'false']);

            if ($myRes['response']['Errors']!=null){
                if($myRes['response']['Errors'][0]['Code'] =="IpNotTrustedException")
                    $response= 'IP معتبر نیست!';
                else
                    $response=$myRes['response']['Errors'][0]['ShortText'];
                $html="<div class=\"btn btn-danger disabled\" >$response</div>";
            }

            else if($myRes['response']['PricedItineraries'] == null){
                $response="چنین پروازی وجود ندارد";

                $html="<div class=\"btn btn-danger disabled\" >$response</div>";
            }
            else {
                $html='';
                $responses = $myRes['response'];


                foreach($responses['PricedItineraries'] as $response){
                    // شرکت هواپیمایی
                    $MarketingAirlineEN=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['MarketingAirline']['Value'];
                    if ($MarketingAirlineEN=="QESHM AIR")
                        $MarketingAirlineFA='قشم ایر';
                    else if ($MarketingAirlineEN=="MERAJ")
                        $MarketingAirlineFA='معراج';
                    else if ($MarketingAirlineEN=="TABAN")
                        $MarketingAirlineFA='تابان ایر';
                    else if ($MarketingAirlineEN=="ZAGROS")
                        $MarketingAirlineFA='زاگرس';
                    else
                        $MarketingAirlineFA=$MarketingAirlineEN;

                    // تجهیزات هواپیمایی
                    $AirEquipType=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['Equipment']['AirEquipType'];

                    //مبدا
                    $DepartureAirport=$response['AirItinerary']['OriginDestinationOptions'][0]['FlightSegment'][0]['DepartureAirport']['LocationCode'];

                    //مقصد
                    $ArrivalAirport=$response['AirItinerary']['OriginDestinationOptions'][0]['FlightSegment'][0]['ArrivalAirport']['LocationCode'];


                    // شماره پرواز
                    $FlightNumber=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['FlightNumber'];

                    $FareBasisCode=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['FareBasisCode'];

                    // زمان حرکت

                    $DepartureDateTime=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['DepartureDateTime'];

                    //زمان رسیدن
                    $ArrivalDateTime=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['ArrivalDateTime'];


                    // ظرفیت
                    $AvailableSeatQuantity=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['AvailableSeatQuantity'];

                    // نوع بلیط
                    $cabinTypeEN=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['CabinType'];

                    if ($cabinTypeEN=="Economy")
                        $cabinTypeFA='اکونومی';
                    else
                        $cabinTypeFA=$cabinTypeEN;

                    $AirEquipType=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['Equipment']['AirEquipType'];

                    //قیمت کل
                     $ItinTotalFare= $response["AirItineraryPricingInfo"]["ItinTotalFare"]["TotalFare"]['Amount'];

                     //قیمت های مجزا
                    $passengerNumber=0;
                     foreach ($response["AirItineraryPricingInfo"]["PTC_FareBreakdowns"] as $prices){
                         $passengerNumber+=$prices["PassengerTypeQuantity"]['Quantity'];
                         $price[$prices["PassengerTypeQuantity"]['Code']]=
                             [$prices["PassengerFare"]['TotalFare']['Amount']/$prices["PassengerTypeQuantity"]
                                 ['Quantity'],$prices["PassengerTypeQuantity"]['Quantity']];

                     }



                     $ADT='';$CHD='';$INF='';$ADTNumber=0;$CHDNumber=0;$INFNumber=0;
                     if (array_key_exists('ADT',$price)){
                         $ADT="<tr>
                                  <th class=\"col - sm - 5\">قیمت برای بزرگسال</th>
                                  <td class=\"col - sm - 5\">".toPersianNum($price['ADT'][0])."</td>
                             </tr>";
                         $ADTNumber=$price['ADT'][1];
                     }
                     else
                         $price['ADT'][0]=0;
                     if (array_key_exists('CHD',$price)){
                         $CHD=
                             "<tr>
                                <th class=\"col-sm-5\">قیمت برای کودک</th>
                                <td class=\"col-sm-5\">".toPersianNum($price['CHD'][0])."</td>
                            </tr>";
                         $CHDNumber=$price['CHD'][1];
                     }
                     else
                         $price['CHD'][0]=0;
                     if (array_key_exists('INF',$price)){
                         $INF="<tr>
                                 <th class=\"col - sm - 5\">قیمت برای نوزاد</th>
                                 <td class=\"col - sm - 5\">".toPersianNum($price['INF'][0])."</td>
                              </tr>";
                         $INFNumber=$price['INF'][1];

                     }
                     else
                         $price['INF'][0]=0;



                    $dateTime=explode('T',$DepartureDateTime);
                    $time=explode(':',$dateTime[1]);

                    session(['data'=>[

                        'DepartureAirport' => $DepartureAirport,
                        'ArrivalAirport' => $ArrivalAirport,

                        'DepartureDateTimeEN' => $DepartureDateTime,
                        'ArrivalDateTimeEN' => $ArrivalDateTime,
                        'DepartureDateTimeFA' => toPersianNum(jdate($DepartureDateTime)->format('%d %B، %Y H:i')),
                        'ArrivalDateTimeFA' => toPersianNum(jdate($ArrivalDateTime)->format('%d %B، %Y H:i')),

                        'DepartureDate' => toPersianNum(jdate($dateTime[0])->format('%A	%d	%B	%Y	')) ,
                        'DepartureTime' => toPersianNum($time[0].':'.$time[1]),

                        'AvailableSeatQuantity' => $AvailableSeatQuantity,

                        'FlightNumber' => $FlightNumber,

                        'FareBasisCode' => $FareBasisCode,

                        'MarketingAirlineEN' => $MarketingAirlineEN,
                        'MarketingAirlineFA' => $MarketingAirlineFA,

                        'cabinTypeEN' => $cabinTypeEN,
                        'cabinTypeFA' => $cabinTypeFA,

                        'AirEquipType'=>$AirEquipType,

                        'passengerNumber'=>$passengerNumber,

                        'price'=>$ItinTotalFare,

                        'ADTNumber'=>$ADTNumber,
                        'CHDNumber'=>$CHDNumber,
                        'INFNumber'=>$INFNumber,

                    ]]);


                    $Departure=explode('T',$DepartureDateTime);
                    $date=toPersianNum(jdate($Departure[0])->format('%d %B، %Y'));
                    $time=toPersianNum($Departure[1]);
                    $DepartureDateTime= $date ." " . $time ;
                    $arrival=explode('T',$ArrivalDateTime);
                    $date=toPersianNum(jdate($arrival[0])->format('%d %B، %Y'));
                    $time=toPersianNum($arrival[1]);
                    $ArrivalDateTime= $date ." " . $time ;





                    $html.="<div class='row'>
                                <div id=\"divContent\" class=\"col-sm-12\" style=\"padding:15px;margin-top: 10px;margin-bottom:10px;min-height: auto;border:1px solid #ddd;border-radius: 3px;overflow: auto\">
                                        <div id=\"div1\" class=\"col-sm-2 col-xs-6\">
                                            <h5 id=\"ch1\">$MarketingAirlineFA</h5>
                                            <br>
                                            <h5 id=\"ch11\">".toPersianNum($FlightNumber)."</h5>

                                        </div>
                                        <div id=\"div2\" class=\"col-sm-4 col-xs-6\">
                                            <h5 id=\"ch2\">تاریخ پرواز</h5>
                                            <br>
                                            <h5 id=\"ch22\">".session('data')['DepartureDateTimeFA']."</h5>
                                        </div>
                                        <div id=\"div4\" class=\"col-sm-2 col-xs-6\">
                                            <h5 id=\"ch4\">ظرفیت</h5>
                                            <br>
                                            <h5 id=\"ch44\">".toPersianNum($AvailableSeatQuantity)." نفر</h5>
                                        </div>
                                        <div id=\"div5\" class=\"col-sm-2 col-xs-6\">
                                            <h5 id=\"ch5\">".session('data')['cabinTypeFA']."</h5>
                                            <br>
                                            <h5 id=\"ch55\">".toPersianNum($price['ADT'][0])."</h5>
                                        </div>
                                        <!-- Button trigger modal -->
                                        <button type=\"button\" id=\"buy\" style=\"margin-top: 30px\" class=\"btn btn-success col-sm-2 col-xs-12\" data-toggle=\"modal\" data-target=\"#exampleModalCenter\">
                                            خرید
                                        </button>

                                        <!-- Modal -->
                                        <div class=\"modal fade\" id=\"exampleModalCenter\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalCenterTitle\" aria-hidden=\"true\">
                                            <div class=\"modal-dialog modal-dialog-centered\" role=\"document\">
                                                <div class=\"modal-content\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                                            <span aria-hidden=\"true\">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class=\"modal-body\">
                                                    
                                                        <div class='row'>
                                                        <table class=\"table table-striped table-responsive col-sm-10 p-4\" style='border-radius: 5px;margin: 0px auto;float: none;' >
                                                            <tr>
                                                              <th class=\"col-sm-5\">مسیر پروازی</th>
                                                              <td class=\"col-sm-5\">از ".CodeToCity($DepartureAirport)." به ".CodeToCity($ArrivalAirport)."</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">شماره پرواز</th>
                                                              <td class=\"col-sm-5\">".toPersianNum($FlightNumber)."</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">هواپیمایی</th>
                                                              <td class=\"col-sm-5\">$MarketingAirlineFA</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">هواپیما</th>
                                                              <td class=\"col-sm-5\">$AirEquipType</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">ظرفیت</th>
                                                              <td class=\"col-sm-5\">".toPersianNum($AvailableSeatQuantity)."  نفر</td>
                                                            </tr>
                                                            $ADT
                                                            $CHD      
                                                            $INF                                                     
                                                            <tr>
                                                              <th class=\"col-sm-5\">تاریخ پرواز</th>
                                                              <td class=\"col-sm-5\">".session('data')['DepartureDateTimeFA']."</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">تاریخ رسیدن به مقصد</th>
                                                              <td class=\"col-sm-5\">".session('data')['ArrivalDateTimeFA']."</td>
                                                            </tr>
                                                        </table>

                                                        </div>

                                                        
                                                    </div>
                                                    <div class=\"modal-footer\">
                                                            <a href='/admin/reservation'><button type=\"button\" class=\"btn btn-primary\" >رزرو</button></a>
                                                            <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">بستن</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>

                    ";



                }//end foreach



            }
        }
        return ['html'=>$html,'date'=>session('Date')];
    }



    public function reservation()
    {
        return view('Panel/reservation',['data'=>session('data')]);
    }

    public function reserve(Request $request){


        $sessionArray=session('data');
        $Number=explode('.',$request['number']);
        $sessionArray['passengerNumber']=$Number[0];
        $sessionArray['ADTNumber']=$Number[1];
        $sessionArray['CHDNumber']=$Number[2];
        $sessionArray['INFNumber']=$Number[3];

        $response=$this->ApiForSearchFlight($sessionArray['DepartureAirport'],$sessionArray['ArrivalAirport'],$sessionArray['DepartureDateTimeEN'],
            $sessionArray['ADTNumber'],$sessionArray['CHDNumber'],$sessionArray['INFNumber']);


        $sessionArray['price']=$response['PricedItineraries'][0]["AirItineraryPricingInfo"]["ItinTotalFare"]["TotalFare"]['Amount'];


//        $request=[
//            "_token" => "UanEGMH8JwDd8qZgOtArxxMMtuV1uqlj0cPDjtkN",
//            "customer-name" => "matin",
//            "email" => "matin.eqbali74@gmail.com",
//            "tel" => "09367687492",
//
//            "gender" => [
//                "0",
//                null,
//                null,
//                null
//            ],
//            "passenger-fname" => [
//                "b1",
//                null,
//                null,
//                null
//            ],
//            "passenger-lname" => [
//                "b11",
//                null,
//                null,
//                null
//            ],
//            "passenger-id" => [
//                "4310924361",
//                null,
//                null,
//                null
//            ],
//            "passenger-birthday" => [
//                "1397/2/18",
//                null,
//                null,
//                null
//            ],
//            "passengerBody" => [
//                "0",
//                "b2",
//                "b22",
//                "4310924362",
//                "1397/2/18",
//                "1",
//                "k1",
//                "k11",
//                "4310924311",
//                "1397/2/18",
//                "0",
//                "n1",
//                "n11",
//                "4310924367",
//                "1397/2/18"
//            ],
//        ];


        $customer['name']=$request['customer-name'];
        $customer['email']=$request['email'];
        $customer['tel']=$request['tel'];


        $j=0;
//        $passenger[0]['type']=$request['type'];
        $passenger[0]['gender']=$request['gender'][0];
        $passenger[0]['fname']=$request['passenger-fname'][0];
        $passenger[0]['lname']=$request['passenger-lname'][0];
        $passenger[0]['id']=$request['passenger-id'][0];
        $passenger[0]['birthday']=$request['passenger-birthday'][0];

        if (isset($request['passengerBody'])){
            $end=count($request['passengerBody'])/5; //5 => number of field of passenger

            for ($i=1;$i<=$end;$i++){
//                $passenger[$i]['type']=$request['type'][$j++];
                $passenger[$i]['gender']=$request['passengerBody'][$j++];
                $passenger[$i]['fname']=$request['passengerBody'][$j++];
                $passenger[$i]['lname']=$request['passengerBody'][$j++];
                $passenger[$i]['id']=$request['passengerBody'][$j++];
                $passenger[$i]['birthday']=$request['passengerBody'][$j++];
            }

            //end=5
            $check_id='false';
            for ($i=0;$i<=$end;$i++) { //0 1 2 3 4 5
                for($j=$i+1;$j<=$end;$j++){
                    if (in_array($passenger[$i]['id'],[$passenger[$j]['id']])){  //0=>1 2 3 4 5  //1=>2 3 4 5 //2=>3 4 5  //3=>4 5 //4=>4 5 //5=>5
                        $check_id='true';
                    }
                }
            }
        }

        session(['dataForPayment' => ['data'=>$sessionArray,'passenger'=>$passenger,'customer'=>$customer] ]);

        return view('Panel.reserve',['data'=>$sessionArray,'passenger'=>$passenger,'customer'=>$customer]);

    }

    public function reserved(){
        $count=count(session('dataForPayment')['passenger']);

        $TravelerInfo[]='';

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
                            "DocID"=> session('dataForPayment')['passenger'][$i]['id'],
                            "DocType"=> 5,
                            "ExpireDate"=> "2020-03-27T13:51:40",
                            "DocIssueCountry"=> "IR",
                            "BirthCountry"=> null,
                            "DocHolderNationality"=> "IR"
                          ],
                          "Gender"=> 0,
                          "BirthDate"=> $this->getDate(session('dataForPayment')['passenger'][$i]['birthday']),
                          "PassengerTypeCode"=> "ADT",
                          "AccompaniedByInfantInd"=> true
                    ] ;

        }





        $json =
                [
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
                        "ID" => "A00001554"
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
                // Set here requred headers
//                "accept: */*",
//                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response=json_decode($response,true);
            return $response;
        }

    }





}










