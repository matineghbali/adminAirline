<?php
namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
                                "Quantity"=> $request['ADT']
                            ],
                            [
                                "Code"=> "CHD",
                                "Quantity"=> $request['CHD']
                            ],
                            [
                                "Code"=> "INF",
                                "Quantity"=> $request['INF']
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
            session(['Response'=>['response'=>$response,'date'=> session('Date')]]);


        }
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

                    // تجهیزات هواپیمایی
                    $AirEquipType=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['Equipment']['AirEquipType'];

                    //مبدا
                    $DepartureAirport=CodeToCity($response['AirItinerary']['OriginDestinationOptions'][0]['FlightSegment'][0]['DepartureAirport']['LocationCode']);

                    //مقصد
                    $ArrivalAirport=CodeToCity($response['AirItinerary']['OriginDestinationOptions'][0]['FlightSegment'][0]['ArrivalAirport']['LocationCode']);


                    // شماره پرواز
                    $FlightNumber=toPersianNum($response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['FlightNumber']);


                    // زمان حرکت

                    $DepartureDateTime=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['DepartureDateTime'];

                    //زمان رسیدن
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

                    //قیمت کل
                     $ItinTotalFare= $response["AirItineraryPricingInfo"]["ItinTotalFare"]["TotalFare"]['Amount'];

                     //قیمت های مجزا
                    $passengerNumber=0;
                     foreach ($response["AirItineraryPricingInfo"]["PTC_FareBreakdowns"] as $prices){
                         $passengerNumber+=$prices["PassengerTypeQuantity"]['Quantity'];
                         $price[$prices["PassengerTypeQuantity"]['Code']]=
                             [toPersianNum($prices["PassengerFare"]['TotalFare']['Amount']/$prices["PassengerTypeQuantity"]['Quantity']),$prices["PassengerTypeQuantity"]['Quantity']];

                     }

                     $ADT='';$CHD='';$INF='';$ADTNumber=0;$CHDNumber=0;$INFNumber=0;
                     if (array_key_exists('ADT',$price)){
                         $ADT="<tr>
                                  <th class=\"col - sm - 5\">قیمت برای بزرگسال</th>
                                  <td class=\"col - sm - 5\">".$price['ADT'][0]."</td>
                             </tr>";
                         $ADTNumber=$price['ADT'][1];
                     }
                     if (array_key_exists('CHD',$price)){
                         $CHD=
                             "<tr>
                                <th class=\"col-sm-5\">قیمت برای کودک</th>
                                <td class=\"col-sm-5\">".$price['CHD'][0]."</td>
                            </tr>";
                         $CHDNumber=$price['CHD'][1];
                     }
                     if (array_key_exists('INF',$price)){
                         $INF="<tr>
                                 <th class=\"col - sm - 5\">قیمت برای نوزاد</th>
                                 <td class=\"col - sm - 5\">".$price['INF'][0]."</td>
                              </tr>";
                         $INFNumber=$price['INF'][1];

                     }

                    $dateTime=explode('T',$DepartureDateTime);
                    $time=explode(':',$dateTime[1]);

                    session(['data'=>[
                        'DepartureAirport'=>$DepartureAirport,
                        'ArrivalAirport' => $ArrivalAirport,
                        'DepartureDate' => toPersianNum(jdate($dateTime[0])->format('%A	%d	%B	%Y	')) ,
                        'DepartureTime' => toPersianNum($time[0].':'.$time[1]),
                        'MarketingAirline' => $MarketingAirline,
                        'FlightNumber' => $FlightNumber,
                        'cabinType' => $cabinType,
                        'passengerNumber'=>$passengerNumber,
                        'price'=>toPersianNum($ItinTotalFare),
                        'AirEquipType'=>$AirEquipType,
                        'ADTNumber'=>$ADTNumber,
                        'CHDNumber'=>$CHDNumber,
                        'INFNumber'=>$INFNumber
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
                                            <h5 id=\"ch44\">$AvailableSeatQuantity نفر</h5>
                                        </div>
                                        <div id=\"div5\" class=\"col-sm-2 col-xs-6\">
                                            <h5 id=\"ch5\">$cabinType</h5>
                                            <br>
                                            <h5 id=\"ch55\">".$price['ADT'][0]."</h5>
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
                                                              <td class=\"col-sm-5\">از $DepartureAirport به $ArrivalAirport</td>
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
                                                              <td class=\"col-sm-5\">$AvailableSeatQuantity نفر</td>
                                                            </tr>
                                                            $ADT
                                                            $CHD      
                                                            $INF                                                     
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



    public function reservation(){
        $passengerInfo="<div class=\"passengerContent\">
                                    <div class=\"passengerHeader\">
                                        <h4>
                                            اطلاعات مسافران (بزرگسال)
                                        </h4>
                                    </div>

                                    <div class=\"row\">
                                        <div class=\"passengerPastPassenger\">
                                            <button type=\"button\" class=\"btn btn-primary btn-xs\"><i class=\"fa fa-th-list\"></i> مسافران سابق</button>
                                            <button class=\"btn btn-danger btn-xs\"><i class=\"fa fa-remove\"></i></button>
                                        </div>

                                    </div>
                                    <div class=\"row passengerInfo\">
                                        <div class=\"col-sm-4\">
                                            <div class=\"form-group\">
                                                <label for=\"sex\" class=\"formLabel\">جنسیت</label>
                                                <select class=\"form-control\" id=\"sex\" name=\"sex\" >
                                                    <option  value=\"female\">انتخاب</option>
                                                    <option  value=\"female\">زن</option>
                                                    <option value=\"male\">مرد</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class=\"col-sm-4\">
                                            <div class=\"form-group \">
                                                <label for=\"customer-name\" class=\"formLabel\">نام</label>
                                                <input class=\"form-control\" type=\"text\" name=\"passenger-fname\" value=".old('passenger-fname').">

                                            </div>
                                        </div>
                                        <div class=\"col-sm-4\">
                                            <div class=\"form-group \">
                                                <label for=\"customer-name\" class=\"formLabel\">نام خانوادگی</label>
                                                <input class=\"form-control\" type=\"text\" name=\"passenger-lname\" value=".old('passenger-lname').">

                                            </div>
                                        </div>
                                        <div class=\"col-sm-4\">
                                            <div class=\"form-group \">
                                                <label for=\"customer-name\" class=\"formLabel\">کد ملی</label>
                                                <input class=\"form-control\" type=\"text\" name=\"passenger-id\" value=".old('passenger-id').">

                                            </div>
                                        </div>
                                        <div class=\"col-sm-4\">
                                            <div class=\"form-group \">
                                                <label for=\"customer-name\" class=\"formLabel\">تاریخ تولد</label>
                                                <input class=\"form-control\" type=\"text\" name=\"passenger-birthday\" value=".old('passenger-birthday').">
                                                    <small id=\"telHelp\" class=\"form-text text-muted\">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                            </div>
                                        </div>
                                    </div>

                               </div>";
        return view('Panel/reservation',['data'=>session('data'),'PassengerInfo'=>$passengerInfo]);
    }

    public function reserve(Request $request){
        return $request->all();
//        return $request->validate([
//            'customer-name'=>'required',
//            'email'=>'required|email',
//            'tel'=>'required|digits:11',
//            'sex' => [
//                'required',
//                Rule::notIn(['select']),
//            ],
//            'passenger-fname'=>'required',
//            'passenger-lname'=>'required',
//            'passenger-id'=>'required|digits:10',
//            'passenger-birthday'=>'required',
//        ]);


    }




}










