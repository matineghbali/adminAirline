<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>matin</title>

    <link rel="stylesheet" href="/assets/css/fontiran.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-rtl.min.css" />
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-select.css" />

    <link type="text/css" rel="stylesheet" href="/assets/css/persianDatepicker-default.css" />



    <script type="text/javascript" src="/assets/js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/persianDatepicker.min.js"></script>


    {{--<script src="/js/jquery.min.js"></script>--}}
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/bootstrap-select.js"></script>
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>--}}






    <script>
        $(document).ready(function () {



            $('#datepicker').persianDatepicker({
                startDate: 'today',
                endDate: '1400/2/2'
            });

            function toPersianNum( num, dontTrim ) {

                var i = 0,

                    dontTrim = dontTrim || false,

                    num = dontTrim ? num.toString() : num.toString().trim(),
                    len = num.length,

                    res = '',
                    pos,

                    persianNumbers = typeof persianNumber == 'undefined' ?
                        ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'] :
                        persianNumbers;

                for (; i < len; i++)
                    if (( pos = persianNumbers[num.charAt(i)] ))
                        res += pos;
                    else
                        res += num.charAt(i);

                return res;
            }

            $('#form').on('submit',function (e) {
                // if ($()==$())
                //     $('#result').html('<div class="alert alert-danger" role="alert">مبدا و مقصد برابر است</div>');

                e.preventDefault();
                var OriginLocation=$('#OriginLocation').val();
                var DestinationLocation=$('#DestinationLocation').val();
                var DepartureDateTime=$('#datepicker').val();
                var adult=$('#adult').val();
                var child=$('#child').val();
                var baby=$('#baby').val();
                var _token=$('input[name="_token"]').val();


                var formData=new  FormData();
                formData.append('OriginLocation',OriginLocation);
                formData.append('DestinationLocation',DestinationLocation);
                formData.append('DepartureDateTime',DepartureDateTime);
                formData.append('adult',adult);
                formData.append('child',child);
                formData.append('baby',baby);
                $.ajax({
                    method: 'POST',
                    url: '/admin/getFlight2',
                    data: formData,
                    contentType : false,
                    processData: false,
                    headers: {
                        'X_CSRF-TOKEN': _token
                    },

                }).done(function (data) {
                    // if (data['response']['Errors']['ShortText']=="IP is not trusted: 5.161.108.59")
                    //     $('#result').html('<div class="alert alert-danger" role="alert">IP معتبر نیست</div>');

                    console.log(data);


                    if (data['DepartureDateTime']!=null){
                        $('#result').html('<div class="alert alert-danger" role="alert">'+data['DepartureDateTime']+'</div>');
                    }
                    else if (data['OriginLocation']!=null){
                        $('#result').html('<div class="alert alert-danger" role="alert">'+data['OriginLocation']+'</div>');
                    }
                    else if (data['DestinationLocation']!=null){
                        $('#result').html('<div class="alert alert-danger" role="alert">'+data['DestinationLocation']+'</div>');
                    }
                    else if(data['response']['PricedItineraries'] == null){
                        $('#result').html('<div class="alert alert-danger" role="alert">چنین پروازی وجود ندارد</div>');

                    }
                    else{
                        if (data['date']!= "false")
                            $('#datepicker').val(data['date']);

                        $('#result').text('');
                        var tbl = $(

                            '                    <table id="table" class="table" >\n' +
                            '                        <thead >\n' +
                            '                        <tr>\n' +
                            '                            <th scope="col">شماره ستون</th>\n' +
                            '                            <th scope="col">شرکت هواپیمایی</th>\n' +
                            '                            <th scope="col">شماره پرواز</th>\n' +
                            '                            <th scope="col">زمان حرکت</th>\n' +
                            '                            <th scope="col">زمان رسیدن به مقصد</th>\n' +
                            '                            <th scope="col">ظرفیت</th>\n' +
                            '                            <th scope="col">نوع بلیط</th>\n' +
                            '                        </tr>\n' +
                            '                        </thead>\n' +
                            '                        <tbody id="tbody">\n' +
                            '\n' +
                            '                        </tbody>\n'
                        ).attr({ id: "bob" });



                        var i=0,j=0;
                        for(j in data['response']['PricedItineraries']) {
                            if (j=='_indexOf')
                                break;
                            var row = $('<tr></tr>').attr({ class: ["class1", "class2", "class3"].join(' ') }).appendTo(tbl);

                            $('<td></td>').text(toPersianNum(++i)).appendTo(row);

                            // شرکت هواپیمایی
                            MarketingAirline=data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['MarketingAirline']['Value'];
                            if (MarketingAirline=="QESHM AIR")
                                $('<td></td>').text('قشم ایر').appendTo(row);
                            else if (MarketingAirline=="MERAJ")
                                $('<td></td>').text('معراج').appendTo(row);
                            else if (MarketingAirline=="TABAN")
                                $('<td></td>').text('تابان').appendTo(row);
                            else if (MarketingAirline=="ZAGROS")
                                $('<td></td>').text('زاگرس').appendTo(row);
                            else
                                $('<td></td>').text(MarketingAirline).appendTo(row);

                            // شماره پرواز
                            $('<td></td>').text(toPersianNum(data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['FlightNumber'])).appendTo(row);

                            // زمان حرکت

                            $('<td></td>').text(data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['DepartureDateTime']).appendTo(row);

                            // var DepartureDateTime=data['response']['PricedItineraries'][i]['AirItinerary']['OriginDestinationOptions']
                            //     [0]['FlightSegment'][0]['DepartureDateTime'];
                            // var myarr=DepartureDateTime.split("T");
                            // alert(myarr[0]+ '     ' +myarr[1]);

                            // $('<td></td>').text().appendTo(row);

                            // زمان رسیدن به مقصد
                            $('<td></td>').text(data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['ArrivalDateTime']).appendTo(row);


                            // ظرفیت
                            $('<td></td>').text(toPersianNum(data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['AvailableSeatQuantity'])).appendTo(row);

                            // نوع بلیط
                            var cabinType=data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['CabinType'];

                            if (cabinType=="Economy")
                                $('<td></td>').text('اکونومی').appendTo(row);
                            else
                                $('<td></td>').text(cabinType).appendTo(row);



                        } //end forin
                        tbl.appendTo($("#result"));


                    } //end else


                });

            })
        })
    </script>

</head>
<body>
<div id="page-wrapper" >
    <div class="container" style="width: 100%">
        <div class="row" >
            <nav class="navbar navbar-default navbar-cls-top" role="navigation" style="margin-bottom: 0">
                <div class="col-sm-3" >
                    <div class="navbar-header col-sm-12">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand " href="index.html">پنل مدیریت</a>
                    </div>

                </div>
                <div class="col-sm-9" >
                    <div style="color: white;
            padding: 15px 50px 5px 50px;
            float: left;
            font-size: 16px;" >
                        {{toPersianNum(jdate()->format('%d %B، %Y'))}}
                        <a href="/logout" class="btn btn-danger">خروج</a>
                    </div>

                </div>

            </nav>

        </div>

    </div>

    <div class="container" style="width: 100%">
        <div class="row">
            <div class="col-sm-3" id="menu" >
                <div class="row " >
                    <nav class="navbar-default navbar-side col-sm-12" role="navigation" >

                        <div class="sidebar-collapse"  >
                            <ul class="nav" id="main-menu" style="margin-left: -15px;margin-right: -15px">
                                <li class="text-center">
                                    <img src="/assets/img/find_user.png" class="user-image img-responsive"/>
                                </li>


                                <li>
                                    <a class="active-menu" href="{{route('adminPanel')}}"><i class="fa fa-dashboard fa-3x"></i> میزکار</a>
                                </li>
                                <li>
                                    <a    href="{{route('getFlight')}}"><i class="fa fa-desktop fa-3x"></i> جستجوی پرواز</a>
                                </li>
                                <li>
                                    <a  href="#"><i class="fa fa-qrcode fa-3x"></i> Tabs & Panels</a>
                                </li>
                                <li  >
                                    <a  href="{{route('adminPanel')}}"><i class="fa fa-bar-chart-o fa-3x"></i> Morris Charts</a>
                                </li>
                                <li  >
                                    <a  href="form.html"><i class="fa fa-edit fa-3x"></i> Forms </a>
                                </li>
                                <li  >
                                    <a   href="login.html"><i class="fa fa-bolt fa-3x"></i> Login</a>
                                </li>
                                <li  >
                                    <a   href="registeration.html"><i class="fa fa-laptop fa-3x"></i> Registeration</a>
                                </li>

                            </ul>

                        </div>

                    </nav>

                </div>

            </div>
            <div class="col-sm-9" id="content" >
                <div class="row " style="background: #5F5D5D;margin-left: 20px;min-height:80px;border-radius: 8px">
                    <h2 style="padding: 10px">
                        {{\Illuminate\Support\Facades\Auth::user()->name}} عزیز خوش آمدید!

                    </h2>

                </div>



            </div>
        </div>
    </div>

</div>








</body>
</html>



</body>
</html>
