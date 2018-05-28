
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" dir="rtl">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>پنل مدیریت</title>

    <link rel="stylesheet" href="/assets/css/fontiran.css">
    <link href="/assets/css/bootstrap.css" rel="stylesheet" />


    <link href="/assets/css/bootstrap-rtl.min.css" rel="stylesheet" />
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/css/custom.css" rel="stylesheet" />
    {{--<link type="text/css" rel="stylesheet" href="/assets/css/persianDatepicker-default.css" />--}}


    <link rel="stylesheet" href="/assets/css/bootstrapValidator.css">
    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrapValidator.js"></script>

    {{--persianDatepicker--}}
    {{--<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>--}}
    {{--<script type="text/javascript" src="/assets/js/persianDatepicker.min.js"></script>--}}


    {{--js for toggleButton--}}
    <script src="/assets/js/jquery.metisMenu.js"></script>
    {{--<script src="/assets/js/custom.js"></script>--}}

    {{--<script type="text/javascript" src="/assets/js/printThis.js"></script>--}}


</head>
<body>
<div id="wrapper">
    <div id="page-wrapper" >
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12" id="printable">
                    <div class="row">
                        <div class="col-md-12" >
                            <h4 style="color: red">مسیر پروازی {{CodeToCity($tickets[0]->flight->DepartureAirport)}} به
                                {{CodeToCity($tickets[0]->flight->ArrivalAirport)}}:</h4>
                            {{--ticket info--}}
                            <div class="passengerContent">
                                <div class="passengerHeader">
                                    <h4 class="h4Passenger">
                                        اطلاعات بلیت ها
                                    </h4>
                                </div>

                                <div class="passengerBody">
                                    <div class="row passengerInfo " style="padding: 10px">
                                        <div class="col-sm-4">
                                            <span>زمان رزرو بلیت:</span>
                                            <b>{{toPersianNum(jdate($tickets[0]->dateBook)->format('H:i، %d %B %Y '))}}</b>
                                        </div>
                                        <div class="col-sm-3">
                                            <span>شماره مرجع:</span>
                                            {{$tickets[0]->BookingReference}}
                                        </div>
                                        <div class="col-sm-2">
                                            <span>تعداد بلیت ها:</span>
                                            <b>{{toPersianNum($tickets[0]->flight->passengerNumber)}}</b>  عدد
                                        </div>
                                        <div class="col-sm-3">
                                            <span>قیمت کل بلیت ها:</span>
                                            <b>{{toPersianNum($tickets[0]->flight->price)}}</b> تومان
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{--Flight Info--}}
                            <div class="passengerContent">
                                <div class="passengerHeader">
                                    <h4 class="h4Passenger">
                                        اطلاعات پرواز
                                    </h4>
                                </div>
                                <div class="row panelContent" >
                                    <div class="col-md-4">
                                        <ul>
                                            <li>زمان حرکت: <b>{{toPersianNum(jdate($tickets[0]->flight->DepartureDateTime)->format('H:i، %d %B %Y '))}}</b></li>
                                            <li>زمان رسیدن: <b>{{toPersianNum(jdate($tickets[0]->flight->ArrivalDateTime)->format('H:i، %d %B %Y '))}}</b></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2" style="padding-top: 20px">
                                        <span class="text-muted" >هواپیمایی
                                            {{\App\Http\Controllers\AdminController::getMarketingAirlineFA($tickets[0]->flight->MarketingAirline)}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <ul>
                                            <li>هواپیما: <b>{{$tickets[0]->flight->AirEquipType}} </b></li>
                                            <li>شماره پرواز: <b>{{toPersianNum($tickets[0]->flight->FlightNumber)}}</b></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul>
                                            <li>پرواز  <b>چارتر </b></li>
                                            <li>کلاس پروازی: <b>{{\App\Http\Controllers\AdminController::getCabinTypeFA($tickets[0]->flight->cabinType)}}</b></li>
                                        </ul>

                                    </div>
                                </div>
                            </div>

                            {{--customer info--}}
                            <div class="passengerContent" id="ADT">
                                <div class="passengerHeader">
                                    <h4 class="h4Passenger">
                                        اطلاعات خریدار
                                    </h4>
                                </div>

                                <div class="passengerBody">
                                    <div class="row passengerInfo " style="padding: 10px">
                                        <div class="col-sm-4">
                                            <span>نام:</span>
                                            {{$tickets[0]->customer_name}}
                                        </div>
                                        <div class="col-sm-4">
                                            <span>ایمیل:</span>
                                            {{$tickets[0]->customer_email}}
                                        </div>
                                        <div class="col-sm-4">
                                            <span>شماره موبایل:</span>
                                            {{$tickets[0]->customer_tel}}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{--Passenger info--}}
                            <div class="passengerContent" style="margin-top: 30px">
                                <div class="passengerHeader">
                                    <h4 class="h4Passenger">
                                        اطلاعات مسافران
                                    </h4>
                                </div>
                                <div class="passengerBody" >
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr class="small">
                                                <th>#</th>
                                                <th>نوع</th>
                                                <th>جنسیت</th>
                                                <th>نام</th>
                                                <th> نام خانوادگی</th>
                                                <th>کد ملی</th>
                                                <th>تاریخ تولد</th>
                                                <th>قیمت بلیت</th>
                                                <th>شماره بلیت</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $i=0 ?>
                                            @foreach($tickets as $ticket)
                                                <tr>
                                                    <td>
                                                        {{toPersianNum(++$i)}}
                                                    </td>
                                                    <td>
                                                        @if($ticket->passenger->type=='ADT')
                                                            بزرگسال
                                                        @elseif($ticket->passenger->type=='CHD')
                                                            کودک
                                                        @else
                                                            نوزاد
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($ticket->passenger->gender==0)
                                                            <b>خانم</b>
                                                        @else
                                                            <b>آقا</b>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$ticket->passenger->fname}}
                                                    </td>
                                                    <td>
                                                        {{$ticket->passenger->lname}}
                                                    </td>
                                                    <td class="nowrap">
                                                        <strong>
                                                            {{toPersianNum($ticket->passenger->doc_id)}}
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        {{toPersianNum($ticket->passenger->birthday)}}
                                                    </td>
                                                    <td>
                                                        {{toPersianNum($ticket->passenger->price)}}
                                                    </td>
                                                    <td>
                                                        {{toPersianNum($ticket->ticketNumber)}}
                                                    </td>
                                                </tr>

                                            @endforeach


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>



            </div>
        </div>
    </div>
</div>
</body>
</html>
