<?php
namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
//        session_destroy();
        session()->forget('Errors');
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
        else{
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
            $response=json_decode($response,true);
            if ($response['PricedItineraries']!= null){
                for ($i=0;$i<count($response['PricedItineraries']);$i++){
                    $arrival=explode('T',$response['PricedItineraries'][$i]['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['DepartureDateTime']);
                    $date=toPersianNum(jdate($arrival[0])->format('%d %B، %Y'));
                    $time=toPersianNum($arrival[1]);
                    $response['PricedItineraries'][0]['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['DepartureDateTime']= $date ." " . $time ;
                    $arrival=explode('T',$response['PricedItineraries'][$i]['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['ArrivalDateTime']);
                    $date=toPersianNum(jdate($arrival[0])->format('%d %B، %Y'));
                    $time=toPersianNum($arrival[1]);
                    $response['PricedItineraries'][0]['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['ArrivalDateTime']= $date ." " . $time ;
                }
            }
            session(['Response'=>['response'=>$response,'date'=> session('Date')]]);


        }
    }



    public function getFlight3(){

        // ارورهای ولیدیشن
        $html='';
        if (session()->has('Errors'))       //error haye validation
        {
            $response=session('Errors');
            if ($response->first('DepartureDateTime'))
                $html.="<div class='row'><div class=\"btn btn-danger disabled col-sm-6\" >".
                    $response->first('DepartureDateTime'). '</div></div><br>';
            if ($response->first('OriginLocation'))
                $html.="<div class='row'><div class=\"btn btn-danger disabled col-sm-6\" >".
                    $response->first('OriginLocation'). '</div></div><br>';
            if ($response->first('DestinationLocation'))
                $html.="<div class='row'><div class=\"btn btn-danger disabled col-sm-6\" >".
                    $response->first('DestinationLocation'). '</div></div><br>';

        }

        // //ارورهای سرور
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
                    $MarketingAirline=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['MarketingAirline']['Value'];
                    if ($MarketingAirline=="QESHM AIR")
                        $MarketingAirline='قشم ایر';
                    else if ($MarketingAirline=="MERAJ")
                        $MarketingAirline='معراج';
                    else if ($MarketingAirline=="TABAN")
                        $MarketingAirline='تابان ایر';
                    else if ($MarketingAirline=="ZAGROS")
                        $MarketingAirline='زاگرس';


                    // شماره پرواز
                    $FlightNumber=toPersianNum($response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['FlightNumber']);


                    // زمان حرکت

                    $DepartureDateTime=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['DepartureDateTime'];

                    $ArrivalDateTime=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['ArrivalDateTime'];




                    // ظرفیت
                    $AvailableSeatQuantity=toPersianNum($response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['AvailableSeatQuantity']);

                    // نوع بلیط
                    $cabinType=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['CabinType'];

                    if ($cabinType=="Economy")
                        $cabinType='اکونومی';

                    $AirEquipType=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['Equipment']['AirEquipType'];

                    $ADT=$response['AirItineraryPricingInfo']['PTC_FareBreakdowns']['PassengerFare']['BaseFare']['Amount'];
//
//                    $CHD=$response['AirItineraryPricingInfo']['PTC_FareBreakdowns']
//                    [1]['PassengerFare']['BaseFare']['Amount'];
//
//                    $INF=$response['AirItineraryPricingInfo']['PTC_FareBreakdowns']
//                    [2]['PassengerFare']['BaseFare']['Amount'];



                    $html.="<div class='row'>
                                <div id=\"divContent\" class=\"col-sm-12\" style=\"padding:15px;margin-top: 10px;margin-bottom:10px;min-height: auto;border:1px solid #6c757d;overflow: auto\">
                                        <div id=\"div1\" class=\"col-sm-2 col-xs-6\">
                                            <h5 id=\"ch1\">$MarketingAirline</h5>
                                            <br>
                                            <h5 id=\"ch11\">$FlightNumber</h5>

                                        </div>
                                        <div id=\"div2\" class=\"col-sm-4 col-xs-6\">
                                            <h5 id=\"ch2\">تاریخ پرواز</h5>
                                            <br>
                                            <h5 id=\"ch22\">$DepartureDateTime</h5>
                                        </div>
                                        <div id=\"div4\" class=\"col-sm-2 col-xs-6\">
                                            <h5 id=\"ch4\">ظرفیت</h5>
                                            <br>
                                            <h5 id=\"ch44\">$AvailableSeatQuantity</h5>
                                        </div>
                                        <div id=\"div5\" class=\"col-sm-2 col-xs-6\">
                                            <h5 id=\"ch5\">نوع بلیت</h5>
                                            <br>
                                            <h5 id=\"ch55\">$cabinType</h5>
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
                                                              <td class=\"col-sm-5\"></td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">شماره پرواز</th>
                                                              <td class=\"col-sm-5\">$FlightNumber</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">هواپیمایی</th>
                                                              <td class=\"col-sm-5\">$MarketingAirline</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">هواپیما</th>
                                                              <td class=\"col-sm-5\">$AirEquipType</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">ظرفیت</th>
                                                              <td class=\"col-sm-5\">$AvailableSeatQuantity</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">قیمت(بزرگسال)</th>
                                                              <td class=\"col-sm-5\">$ADT</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">قیمت برای کودک</th>
                                                              <td class=\"col-sm-5\"></td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">قیمت برای نوزاد</th>
                                                              <td class=\"col-sm-5\"></td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">تاریخ پرواز</th>
                                                              <td class=\"col-sm-5\">$DepartureDateTime</td>
                                                            </tr>
                                                            <tr>
                                                              <th class=\"col-sm-5\">تاریخ رسیدن به مقصد</th>
                                                              <td class=\"col-sm-5\">$ArrivalDateTime</td>
                                                            </tr>
                                                        </table>

                                                        </div>

                                                        
                                                    </div>
                                                    <div class=\"modal-footer\">
                                                        <button type=\"button\" class=\"btn btn-primary\">رزرو</button>
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


}
