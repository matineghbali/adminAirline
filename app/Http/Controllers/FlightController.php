<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightController extends AdminController
{
    //    start of search flight


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

        $err='false';
        // ارورهای ولیدیشن
        $html='';
        if (session()->has('Errors'))       //error haye validation
        {
            $error=session('Errors');
            if ($error->first('DepartureDateTime'))
                $html='<script>SweetAlert({   title: "ارور",   text: "'.$error->first('DepartureDateTime').'",type: "error", confirmButtonText: "باشه"})</script>';
            elseif ($error->first('OriginLocation'))
                $html='<script>SweetAlert({   title: "ارور",   text: "'.$error->first('OriginLocation').'",type: "error", confirmButtonText: "باشه"})</script>';
            elseif ($error->first('DestinationLocation'))
                $html='<script>SweetAlert({   title: "ارور",   text: "'.$error->first('DestinationLocation').'",type: "error", confirmButtonText: "باشه"})</script>';
            $err='true';
        }

        //اارور های تعداد مسافران
        elseif (session()->has('PassengerNumERR')){
            $error=session('PassengerNumERR');
            $html='<script>SweetAlert({   title: "ارور",   text: "'.$error.'",type: "error", confirmButtonText: "باشه"})</script>';
            $err='true';
        }
        //ارورهای سرور
        else{
            $myRes=session('Response');

            if ($myRes['response']['Errors']!=null){
                if($myRes['response']['Errors'][0]['Code'] =="IpNotTrustedException")
                    $response= 'IP معتبر نیست!';
                else
                    $response=$myRes['response']['Errors'][0]['ShortText'];
                $html='<script>SweetAlert({   title: "ارور",   text: "'.$response.'",type: "error", confirmButtonText: "باشه"})</script>';
                $err='true';
            }

            else if($myRes['response']['PricedItineraries'] == null){
                $html='<script>SweetAlert({   title: "ارور",   text: "چنین پروازی وجود ندارد",type: "error", confirmButtonText: "باشه"})</script>';
                $err='true';

            }
            else {
                $html='';
                $responses = $myRes['response'];


                foreach($responses['PricedItineraries'] as $response){
                    // شرکت هواپیمایی
                    $MarketingAirlineEN=$response['AirItinerary']['OriginDestinationOptions']
                    [0]['FlightSegment'][0]['MarketingAirline']['Value'];
                    $MarketingAirlineFA=$this->getMarketingAirlineFA($MarketingAirlineEN);
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
                    $cabinTypeFA=$this->getCabinTypeFA($cabinTypeEN);

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


//                    set price for each passenger type

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
//        session()->forget('Response');

        return ['html'=>$html,'error'=>$err];
    }

//    end of search flight

}
