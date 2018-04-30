
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
    <script type="text/javascript" src="/assets/js/bootstrapValidator.js"></script>
    <script type="text/javascript" src="/assets/js/reservation.js"></script>

    {{--persianDatepicker--}}
    {{--<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>--}}
    <script type="text/javascript" src="/assets/js/persianDatepicker.min.js"></script>


    {{--js for toggleButton--}}
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery.metisMenu.js"></script>
    {{--<script src="/assets/js/custom.js"></script>--}}


    <script>
        $(document).ready(function() {
            var ADTNumber= {{$data['ADTNumber']}};
            var CHDNumber= {{$data['CHDNumber']}};
            var INFNumber= {{$data['INFNumber']}};


            for(i=1;i<ADTNumber;i++){
                var template     = "passengerBody",
                    $templateEle = $('#' + template + 'TemplateADT'),
                    $row         = $templateEle.clone().removeAttr('id').insertBefore($templateEle).removeClass('hide');

                var $el= $row.find('select').eq(0).attr('name', template + '[]');
                $('#defaultForm').bootstrapValidator('addField', $el);


                for (j=0;j<=3;j++)
                {
                    var  $el = $row.find('input').eq(j).attr('name', template + '[]');
                    $('#defaultForm').bootstrapValidator('addField', $el);

                }



                $row.on('click', '.removeButton', function(e) {
                    $('#defaultForm').bootstrapValidator('removeField', $el);
                    $row.remove();
                });
            }

            if (CHDNumber>0){
                // $("#ADT").after($("#ADT0").clone().attr('id','CHD0'));
                // $("#CHD .h4Passenger").text('اطلاعات مسافران (کودک)');
                // $('#CHD .passengerBody').remove();
                $('#CHD').css("visibility", "visible");
                $('#CHD').append($('#passengerBodyTemplateADT').clone().attr('id','passengerBodyTemplateCHD'))
                for(i=0;i<CHDNumber;i++){
                    var template     = "passengerBody",
                        $templateEle = $('#' + template + 'TemplateCHD'),
                        $row         = $templateEle.clone().removeAttr('id').insertBefore($templateEle).removeClass('hide');

                    var    $el          = $row.find('select').eq(0).attr('name', template + '[]');
                    $('#defaultForm').bootstrapValidator('addField', $el);


                    for (j=0;j<=3;j++)
                    {
                        var  $el = $row.find('input').eq(j).attr('name', template + '[]');
                        $('#defaultForm').bootstrapValidator('addField', $el);
                        // $el.attr('placeholder', 'Textbox #' + index);

                    }



                    $row.on('click', '.removeButton', function(e) {
                        $('#defaultForm').bootstrapValidator('removeField', $el);
                        $row.remove();
                    });
                }
            }

            if (INFNumber>0){
                $('#INF').css("visibility", "visible");
                $('#INF').append($('#passengerBodyTemplateADT').clone().attr('id','passengerBodyTemplateINF'))
                for(i=0;i<INFNumber;i++){
                    var template     = "passengerBody",
                        $templateEle = $('#' + template + 'TemplateINF'),
                        $row         = $templateEle.clone().removeAttr('id').insertBefore($templateEle).removeClass('hide');

                    var $el= $row.find('select').eq(0).attr('name', template + '[]');
                    $('#defaultForm').bootstrapValidator('addField', $el);


                    for (j=0;j<=3;j++)
                    {
                        var  $el = $row.find('input').eq(j).attr('name', template + '[]');
                        $('#defaultForm').bootstrapValidator('addField', $el);
                        // $el.attr('placeholder', 'Textbox #' + index);

                    }


                    $row.on('click', '.removeButton', function(e) {
                        $('#defaultForm').bootstrapValidator('removeField', $el);
                        $row.remove();
                    });
                }
            }


        });




    </script>



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
                    <a   href="{{route('getFlight')}}" ><i class="fa fa-desktop fa-3x"></i>بلیط هواپیما</a>
                </li>
            </ul>

        </div>

    </nav>
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper" >
        <div id="page-inner">

            adt:{{$data['ADTNumber']}}
            chd:{{$data['CHDNumber']}}
            inf:{{$data['INFNumber']}}
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-12" >
                            <div class="panelTitle">اطلاعات بلیط {{$data['DepartureAirport']}} به {{$data['ArrivalAirport']}} {{$data['DepartureDate']}}</div>

                            <div class="panel">
                                <div class="row panelContent" >
                                    <div class="col-md-3">
                                        <h3>{{$data['DepartureTime']}}</h3>
                                        <span>{{$data['DepartureAirport']}}  {{$data['ArrivalAirport']}}</span>
                                    </div>
                                    <div class="col-md-2" style="padding-top: 20px">
                                        <span class="text-muted" >هواپیمایی {{$data['MarketingAirline']}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <ul>
                                            <li>هواپیما: <b>{{$data['AirEquipType']}} </b></li>
                                            <li>شماره پرواز: <b>{{$data['FlightNumber']}}</b></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul>
                                            <li>پرواز  <b>چارتر </b></li>
                                            <li>کلاس پروازی: <b>{{$data['cabinType']}}</b></li>
                                        </ul>

                                    </div>
                                    <div class="col-md-3">
                                        <h3>{{$data['passengerNumber']}} نفر </h3>
                                        <span>{{$data['price']}} تومان</span>
                                    </div>

                                </div>
                            </div>



                            <form id="defaultForm" method="post" action="target.php">
                                {{csrf_field()}}
                                <div class="formContent">
                                    {{--customer info--}}
                                    <div class="customerInfo">
                                        <div class="row" >
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام و نام خانوادگی</label>
                                                    <input class="form-control" type="text" name="customer-name" >

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="email1" class="formLabel">ایمیل</label>
                                                    <input type="text" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                                                        <small id="emailHelp" class="form-text text-muted">پس از خرید، بلیط به ایمیل شما ارسال می گردد.</small>

                                                </div>

                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="tel" class="formLabel">شماره تماس</label>
                                                    <input type="tel" class="form-control" id="tel" aria-describedby="telHelp" name="tel">
                                                        <small id="telHelp" class="form-text text-muted">مثال: ۰۹۱۲۱۲۳۴۵۶۷</small>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <small style="color: #0275d8">این قسمت مربوط به مشخصات خریدار است.</small>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{--add field--}}

                                <div class="addFieldContent">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-7 m-passengers__section addFieldLabel">
                                            مشخصات مسافران را وارد کنید:
                                        </div>
                                        <div class="col-md-6 col-lg-5 addField">
                                            <button type="button" class="btn add-passenger m-passengers__addp addFieldBtn addADT" data-template="passengerBody">
                                                <span class="addFieldSpan" >
                                                    <svg class="addFieldSVG"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg>
                                                </span>
                                                بزرگسال
                                            </button>
                                            <button type="button" class="btn add-passenger m-passengers__addp addFieldBtn addCHD" data-template="passengerBody">
                                                <span class="addFieldSpan" >
                                                    <svg class="addFieldSVG"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg>
                                                </span>
                                                کودک
                                            </button>
                                            <button type="button" class="btn add-passenger m-passengers__addp addFieldBtn addINF" data-template="passengerBodyTemplateADT">
                                                <span class="addFieldSpan" >
                                                    <svg class="addFieldSVG"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg>
                                                </span>
                                                نوزاد
                                            </button>

                                        </div>
                                    </div>

                                </div>

                                {{--adult info--}}
                                <div class="passengerContent" id="ADT">
                                    <div class="passengerHeader">
                                        <h4 class="h4Passenger">
                                            اطلاعات مسافران (بزرگسال)
                                        </h4>
                                    </div>

                                    <div class="passengerBody" id="ADT0">
                                        <div class="row">
                                            <div class="passengerPastPassenger">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-th-list"></i> مسافران سابق</button>
                                                <button type="button" class="btn btn-danger btn-xs"><i class="fa fa-remove removeButton"></i></button>
                                            </div>

                                        </div>
                                        <div class="row passengerInfo">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="sex" class="formLabel">جنسیت</label>
                                                    <select class="form-control" name="gender[]" required>
                                                        <option value="">انتخاب</option>
                                                        <option value="female">زن</option>
                                                        <option value="male">مرد</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام</label>
                                                    <input class="form-control" type="text" name="passenger-fname[]">

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام خانوادگی</label>
                                                    <input class="form-control" type="text" name="passenger-lname[]">

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">کد ملی</label>
                                                    <input class="form-control" type="text" name="passenger-id[]">

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel" >تاریخ تولد</label>
                                                    <input class="form-control" type="text" name="passenger-birthday[]">
                                                        <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="passengerBody hide" id="passengerBodyTemplateADT">
                                        <div class="row">
                                            <div class="passengerPastPassenger">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-th-list"></i> مسافران سابق</button>
                                                <button type="button" class="btn btn-danger btn-xs"><i class="fa fa-remove removeButton"></i></button>
                                            </div>

                                        </div>
                                        <div class="row passengerInfo">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="sex" class="formLabel">جنسیت</label>
                                                    <select class="form-control" name="gender[]" required>
                                                        <option value="">انتخاب</option>
                                                        <option value="female">زن</option>
                                                        <option value="male">مرد</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام</label>
                                                    <input class="form-control" type="text" name="passenger-fname[]">

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام خانوادگی</label>
                                                    <input class="form-control" type="text" name="passenger-lname[]">

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">کد ملی</label>
                                                    <input class="form-control" type="text" name="passenger-id[]">

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel" >تاریخ تولد</label>
                                                    <input class="form-control" type="text" name="passenger-birthday[]">
                                                    <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{--CHD info--}}
                                <div class="passengerContent" id="CHD" style="visibility: hidden">
                                    <div class="passengerHeader">
                                        <h4 class="h4Passenger">
                                            اطلاعات مسافران (کودک)
                                        </h4>
                                    </div>

                                </div>

                                {{--INF info--}}
                                <div class="passengerContent" id="INF" style="visibility: hidden">
                                    <div class="passengerHeader">
                                        <h4 class="h4Passenger">
                                            اطلاعات مسافران (نوزاد)
                                        </h4>
                                    </div>

                                </div>




                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-6">
                                        <div class="passengerBtn">
                                            <button class="btn btn-primary btn-block" type="submit">ثبت اطلاعات</button>
                                            <button type="button" class="btn btn-info" id="validateBtn">Manual validate</button>

                                        </div>
                                    </div>

                                </div>
                            </form>




                        </div>

                    </div>
                </div>
            </div>
            <!-- /. ROW  -->

@include('Section.Footer')