<?php
require_once __DIR__ . '/../../../app/Http/Function/funnction.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>پنل مدیریت</title>

    <link rel="stylesheet" href="/assets/css/fontiran.css">
    <link href="/assets/css/bootstrap.css" rel="stylesheet" />


    <link href="/assets/css/bootstrap-rtl.min.css" rel="stylesheet" />
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/css/custom.css" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="/assets/css/persianDatepicker-default.css" />


    <link rel="stylesheet" href="/assets/css/bootstrapValidator.css">
    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrapValidator.js"></script>

    {{--persianDatepicker--}}
    {{--<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>--}}
    <script type="text/javascript" src="/assets/js/persianDatepicker.min.js"></script>


    {{--js for toggleButton--}}
    <script src="/assets/js/jquery.metisMenu.js"></script>
    {{--<script src="/assets/js/custom.js"></script>--}}


</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-cls-top " role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route('adminPanel')}}">پنل مدیریت</a>
        </div>
        <div class="navbar-header-logout">
            {{toPersianNum(jdate()->format('%d %B، %Y'))}}
            <a href="/logout" class="btn btn-danger">خروج</a>
        </div>
    </nav>
    <!-- /. NAV TOP  -->
    <nav class="navbar-default navbar-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">
                <li class="text-center">
                    <img src="/assets/img/find_user.png" class="user-image img-responsive"/>
                </li>
                <li>
                    <a   href="{{route('adminPanel')}}" ><i class="fa fa-dashboard fa-3x"></i> میزکار</a>
                </li>
                <li>
                    <a href="{{route('getFlight')}}" ><i class="fa fa-desktop fa-3x"></i>بلیط هواپیما</a>
                </li>
            </ul>

        </div>

    </nav>
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper" >
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-12" >

                            {{--Flight Info--}}
                            <div class="panelTitle">اطلاعات بلیط {{CodeToCity($data['DepartureAirport'])}} به {{CodeToCity($data['ArrivalAirport'])}}
                                {{$data['DepartureDate']}}</div>
                            <div class="panel">
                                <div class="row panelContent" >
                                    <div class="col-md-3">
                                        <h3>{{$data['DepartureTime']}}</h3>
                                        <span>{{CodeToCity($data['DepartureAirport'])}}  {{CodeToCity($data['ArrivalAirport'])}}</span>
                                    </div>
                                    <div class="col-md-2" style="padding-top: 20px">
                                        <span class="text-muted" >هواپیمایی {{$data['MarketingAirlineFA']}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <ul>
                                            <li>هواپیما: <b>{{$data['AirEquipType']}} </b></li>
                                            <li>شماره پرواز: <b>{{toPersianNum($data['FlightNumber'])}}</b></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul>
                                            <li>پرواز  <b>چارتر </b></li>
                                            <li>کلاس پروازی: <b>{{$data['cabinTypeFA']}}</b></li>
                                        </ul>

                                    </div>
                                    <div class="col-md-3">
                                        <h3>{{toPersianNum($data['passengerNumber'])}} نفر </h3>
                                        <span>{{toPersianNum($data['price'])}} تومان</span>
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
                                            {{$customer['name']}}
                                        </div>
                                        <div class="col-sm-4">
                                            <span>ایمیل:</span>
                                            {{$customer['email']}}
                                        </div>
                                        <div class="col-sm-4">
                                            <span>شماره موبایل:</span>
                                            {{toPersianNum($customer['tel'])}}
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
                                                <th>نام و نام خانوادگی</th>
                                                <th>کد ملی</th>
                                                <th>تاریخ تولد</th>
                                                <th>قیمت بلیت</th>

                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $i=0 ?>
                                            @foreach($passenger as $item)
                                                <tr>
                                                    <td>
                                                        {{toPersianNum(++$i)}}
                                                    </td>
                                                    <td>
                                                        @if($item['type']=='ADT')
                                                            بزرگسال
                                                        @elseif($item['type']=='CHD')
                                                            کودک
                                                        @else
                                                            نوزاد
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item['gender']==0)
                                                            <b>خانم</b>
                                                        @else
                                                            <b>آقا</b>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$item['fname'].' '. $item['lname'] }}
                                                    </td>
                                                    <td class="nowrap">
                                                        <strong>
                                                            {{toPersianNum($item['doc_id'])}}
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        {{toPersianNum($item['birthday'])}}
                                                    </td>
                                                    <td>
                                                        {{toPersianNum($data[$item['type'].'Price'])}}
                                                    </td>

                                                </tr>

                                            @endforeach


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>



                            {{--submit --}}
                            <div class="row" style="margin-top: 20px">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="passengerBtn">
                                                <a href="{{route('reservation')}}">
                                                    <button class="btn btn-primary btn-block" onclick="$('#registerpage').css('visibility','hidden');" type="button" id="editBtn">اصلاح اطلاعات</button>
                                                </a>
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="passengerBtn">
                                                    <button class="btn btn-primary btn-block" type="button" id="reserveBtn">رزرو بلیت</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- /. ROW  -->
            {{--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>--}}
            <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>

            <script>
                $('#reserveBtn').click(function () {
                    $.ajax({
                        method: 'get',
                        url: '/admin/reserved',
                        contentType : false,
                        processData: false

                    }).done(function (data) {
                        console.log(data);

                        if (data['status']=='Error'){
                            swal({   title: "ارور!",   text: data['response'] ,type: "error" , confirmButtonText: 'اصلاح اطلاعات'}).
                            then(function() {
                                window.location.replace("{{route('reservation')}}");
                            });
                        }
                        else {

                            SweetAlert({   title: "با موفقیت انجام شد:)",   text: 'شماره مرجع: ' + data['response'],type: "success" , confirmButtonText: 'مشاهده بلیت'}).
                                then(function() {
                                    window.location.replace("{{route('ticket')}}");

                            });

                        }

                    });
                })
            </script>

        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->




</body>
</html>
