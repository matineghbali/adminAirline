<?php
namespace App\Http\Controllers;

require_once __DIR__ . '/../Function/funnction.php';

use App\Passenger;
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

    public function getBirthday($passenger){
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

        return ['start'=> $start,'end'=>$end];
    }

//    end functions


//    start of search flight

    public function index(){
        return view('Panel/panel');

    }

    public function getFlight(){
        return view('Panel/flight');

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
            $response=$this->ApiForSearchFlight($request['OriginLocation'],$request['DestinationLocation'],$this->DateFormatOfAPI($request['DepartureDateTime']),
            $request['ADT'],$request['CHD'],$request['INF']);

            session(['Response'=>['response'=>$response]]);


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

                        'ADTPrice'=>$price['ADT'][0],
                        'CHDPrice'=>$price['CHD'][0],
                        'INFPrice'=>$price['INF'][0],



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
                
                                                    <form action='/admin/reservation' method='get'>
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
                                                                <button type=\"submit\" class=\"btn btn-primary\" >رزرو</button>
                                                                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">بستن</button>
                                                        </div>
 
                                                   
                                                    </form>
                
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>

                    ";



                }//end foreach



            }
        }
        session()->forget('dataForPayment');

        return ['html'=>$html];
    }

//    end of search flight


//    start of reserve flight

    public function reservation()
    {
        return view('Panel/reservation',['data'=>session('data')]);
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

//      add passengers in passenger table

        $count=count($gender);

        for ($i=0;$i<$count;$i++){
            $passenger= Passenger::where('user_id',auth()->user()->id)->
            where(function ($q) use ($doc_id,$fname,$lname,$i) {
                $q->where('doc_id',$doc_id[$i])
                    ->orWhere(function ($query) use ($fname,$lname,$i){
                        $query->where('fname',$fname[$i])
                            ->orWhere('lname',$lname[$i]);
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
                    'email'=>$request['customer_email'],
                    'tel'=>$request['customer_tel'],
                    'reserve'=>1

                ]);
//                echo "update <br>";

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
                    'email'=>$request['customer_email'],
                    'tel'=>$request['customer_tel'],
                    'reserve'=>1
                ]);
//                echo 'create <br>';


            }
        };

        $passenger= Passenger::where('user_id',auth()->user()->id)->where('reserve',1)->latest()->get();


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



        session(['dataForPayment' => ['data'=>$sessionArray,'passenger'=>$passenger,'customer'=>$customer] ]);
        session(['data'=>session('dataForPayment')['data']]) ;



        $html="
            <div class=\"row\">
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
                            "DocID"=> session('dataForPayment')['passenger'][$i]['id'],
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
            $response=$response['AirReservation']['BookingReferenceID']['ID'];
            $status='Success';
            session(['ticketResponse' => $response]);
        }
        $this->unReserve();

    }

    public function ticket(){
                return session('ticketResponse');

        return view('Panel.ticket',[
            'data'=>session('dataForPayment')['data'],
            'passenger'=>session('dataForPayment')['passenger'],
            'customer'=>session('dataForPayment')['customer'],
            'ticketInfo' => session('ticketResponse')]);

    }


//    end of reserve flight

}










