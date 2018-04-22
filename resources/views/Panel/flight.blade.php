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


                    console.log(data);

                    $('#divError').attr('style','visibility:visible');




                    // ارورهای ولیدیشن
                     if (data['DepartureDateTime']!=null){
                         $('#divContent').attr('style','visibility:hidden');
                         $('#divError').html('<div class="alert alert-danger" role="alert">'+data['DepartureDateTime']+'</div>');
                    }
                    else if (data['OriginLocation']!=null){
                         $('#divContent').attr('style','visibility:hidden');
                        $('#divError').html('<div class="alert alert-danger" role="alert">'+data['OriginLocation']+'</div>');
                    }
                    else if (data['DestinationLocation']!=null){
                         $('#divContent').attr('style','visibility:hidden');
                        $('#divError').html('<div class="alert alert-danger" role="alert">'+data['DestinationLocation']+'</div>');
                    }


                    //ارورهای سرور
                    else if (data['response']['Errors']!=null){
                         $('#divContent').attr('style','visibility:hidden');
                         if(data['response']['Errors'][0]['Code']=="IpNotTrustedException")
                            $('#divError').html('<div class="alert alert-danger" role="alert">IP معتبر نیست.</div>');
                         else
                             $('#divError').html('<div class="alert alert-danger" role="alert">'+data['response']['Errors'][0]['ShortText']+'</div>');

                    }

                    else if(data['response']['PricedItineraries'] == null){
                         $('#divContent').attr('style','visibility:hidden');
                        $('#divError').html('<div class="alert alert-danger" role="alert">چنین پروازی وجود ندارد</div>');
                    }
                    else{
                        $('#divError').html('');

                            if (data['date']!= "false")
                            $('#datepicker').val(data['date']);

                         $('#divContent').attr('style','visibility:visible');


                         var i=0,j=0;
                        for(j in data['response']['PricedItineraries']) {
                            if (j=='_indexOf')
                                break;


                        //     // شرکت هواپیمایی
                            MarketingAirline=data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['MarketingAirline']['Value'];
                            if (MarketingAirline=="QESHM AIR")
                                $('#ch1').text('قشم ایر');
                            else if (MarketingAirline=="MERAJ")
                                $('#ch1').text('معراج');
                            else if (MarketingAirline=="TABAN")
                                $('#ch1').text('تابان ایر');
                            else if (MarketingAirline=="ZAGROS")
                                $('#ch1').text('زاگرس');
                            else
                                $('#ch1').text(MarketingAirline);


                        //     // شماره پرواز
                            $('#ch11').text(toPersianNum(data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['FlightNumber']));
                        //
                        //     var div2=$('<div id="div2" class="col-sm-2 col-xs-6"></div>').after('#div1');
                        //
                        //
                        //     // // زمان حرکت

                            $('#ch22').text(data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['DepartureDateTime']);


                        //     // // زمان رسیدن به مقصد
                            $('#ch33').text(data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['ArrivalDateTime']);


                        //      ظرفیت
                            $('#ch44').text(toPersianNum(data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['AvailableSeatQuantity']));

                         // نوع بلیط
                            var cabinType=data['response']['PricedItineraries'][j]['AirItinerary']['OriginDestinationOptions']
                                [0]['FlightSegment'][0]['CabinType'];

                            if (cabinType=="Economy")
                                $('#ch5').text('اکونومی');
                            else
                                $('#ch5').text(cabinType);



                        } //end forin


                    } //end else


                });

            })
        })
    </script>

</head>
<body>
<div id="page-wrapper" >

    {{--header--}}
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
            {{--left menu--}}
            <div class="col-sm-3" id="menu" >
                <div class="row " >
                    <nav class="navbar-default navbar-side col-sm-12" role="navigation" >

                        <div class="sidebar-collapse"  >
                            <ul class="nav" id="main-menu" style="margin-left: -15px;margin-right: -15px">
                                <li class="text-center">
                                    <img src="/assets/img/find_user.png" class="user-image img-responsive"/>
                                </li>


                                <li>
                                    <a  href="{{route('adminPanel')}}"><i class="fa fa-dashboard fa-3x"></i> میزکار</a>
                                </li>
                                <li>
                                    <a class="active-menu"   href="{{route('getFlight')}}"><i class="fa fa-desktop fa-3x"></i> جستجوی پرواز</a>
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
            {{--content--}}
            <div class="col-sm-9" id="content" >
                    {{--search fields--}}
                    <div class="row " style="background: #5F5D5D;margin-left: 20px;min-height:80px;border-radius: 8px;">
                        <div  style="padding: 20px">
                            <form id="form">
                                {{csrf_field()}}

                                <div class="row">
                                    <div class="col-sm-2 form-group">
                                        <input type="text"  id="datepicker" class="form-control"  placeholder="تاریخ پرواز" >
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <select data-live-search="true" id="OriginLocation" tabindex="0" data-live-search-style="startsWith" class="selectpicker form-control" >
                                            <option id="firstOpt" value="" data-iata=""  disabled selected=""  style="visibility: hidden">مبدأ</option>
                                            <option value="THR" >تهران</option>
                                            <option value="MHD">مشهد</option>
                                            <option value="KIH">کیش</option>
                                            <option value="AWZ">اهواز</option>
                                            <option value="SYZ">شیراز</option>
                                            <option value="IFN">اصفهان</option>
                                            <option value="GSM">قشم</option>
                                            <option value="TBZ">تبریز</option>
                                            <option value="ADU">اردبیل</option>
                                            <option value="BND">بندرعباس</option>
                                            <option value="OMH">ارومیه</option>
                                            <option value="QKC">کرج</option>
                                            <option value="NSH">نوشهر</option>
                                            <option value="QOM">قم</option>
                                            <option value="ABD">آبادان</option>
                                            <option value="AEU">جزیره ابوموسی</option>
                                            <option value="KER">کرمان</option>
                                            <option value="HDM">همدان</option>
                                            <option value="SRY">ساری</option>
                                            <option value="AZD">یزد</option>
                                            <option value="SDG">سنندج</option>
                                            <option value="PGU">عسلویه</option>
                                            <option value="KSH">کرمانشاه</option>
                                            <option value="AFZ">سبزوار</option>
                                            <option value="RAS">رشت</option>
                                            <option value="RZR">رامسر</option>
                                            <option value="DEF">دزفول</option>
                                            <option value="BJB">بجنورد</option>
                                            <option value="YES">یاسوج</option>
                                            <option value="KHY">خوی</option>
                                            <option value="PFQ">پارس آباد</option>
                                            <option value="BXR">بم</option>
                                            <option value="IIL">ایلام</option>
                                            <option value="KHK">خارک</option>
                                            <option value="BUZ">بوشهر</option>
                                            <option value="JWN">زنجان</option>
                                            <option value="TCX">طبس</option>
                                            <option value="GBT">گرگان</option>
                                            <option value="NJF">نجف</option>
                                            <option value="BGW">بغداد</option>
                                            <option value="IST">استانبول</option>
                                            <option value="KWI">کویت</option>
                                            <option value="KHD">خرم آباد</option>
                                            <option value="ZAH">زاهدان</option>
                                            <option value="JAR">جهرم</option>
                                            <option value="MRX">ماهشهر</option>
                                            <option value="DXB">دبی</option>
                                            <option value="ISU">سلیمانیه</option>
                                            <option value="ISE">اسپارتا</option>
                                            <option value="ZBR">چابهار</option>
                                            <option value="AYT">آنتالیا</option>
                                            <option value="TBS">تفلیس</option>
                                            <option value="ADB">ازمیر</option>
                                            <option value="KUL">کوالالامپور</option>
                                            <option value="CAN">گوانجو</option>
                                            <option value="EVN">ایروان</option>
                                            <option value="SAW">سابیها</option>
                                            <option value="BOM">بمبئی</option>
                                            <option value="ACZ">زابل</option>
                                            <option value="BSR">بصره</option>
                                            <option value="BEY">بیروت</option>
                                            <option value="XBJ">بیرجند</option>
                                            <option value="BKK">بانکوک</option>
                                            <option value="CQD">شهرکرد</option>
                                            <option value="PEK">پکن</option>
                                            <option value="EBL">اربیل</option>
                                            <option value="RJN">رفسنجان</option>
                                            <option value="GCH">گچساران</option>
                                            <option value="IHR">ایرانشهر</option>
                                            <option value="BDH">بندر لنگه</option>
                                            <option value="JYR">جیرفت</option>
                                            <option value="SYJ">سیرجان</option>
                                            <option value="AJK">اراک</option>
                                            <option value="DYU">دوشنبه</option>
                                            <option value="KBL">کابل</option>
                                            <option value="LRR">لار</option>
                                            <option value="ACP">مراغه</option>
                                            <option value="RUD">شاهرود</option>
                                            <option value="BAK">باکو</option>
                                            <option value="ASF">آستراخان</option>
                                            <option value="BUS">باتومی</option>
                                            <option value="MOW">مسکو</option>
                                            <option value="IEV">کی یف</option>
                                            <option value="ANK">آنکارا</option>
                                            <option value="KSN">کاشان</option>
                                            <option value="ADA">آدنا</option>
                                            <option value="LFM">لامرد</option>
                                            <option value="MCT">مسقط</option>
                                            <option value="TSE">آستانه</option>
                                            <option value="AMS">آمستردام</option>
                                            <option value="BAH">بحرین</option>
                                            <option value="CGN">کلن</option>
                                            <option value="CPH">کپنهاگ</option>
                                            <option value="DAM">دمشق</option>
                                            <option value="DOH">دوحه</option>
                                            <option value="ROM">رم</option>
                                            <option value="FRA">فرانکفورت</option>
                                            <option value="GOT">گوتنبرگ</option>
                                            <option value="HAM">هامبورگ</option>
                                            <option value="KHI">کراچی</option>
                                            <option value="MIL">میلان</option>
                                            <option value="PAR">پاریس</option>
                                            <option value="STO">استکهلم</option>
                                            <option value="TAS">تاشکند</option>
                                            <option value="VIE">وین</option>
                                            <option value="VIL">داخله</option>
                                            <option value="LED">سنت پترزبورگ</option>
                                            <option value="VAR">وارنا</option>
                                            <option value="LON">لندن</option>
                                            <option value="DNZ">دنیزلی</option>
                                            <option value="AKW">امیدیه</option>
                                            <option value="PVG">شانگهای</option>
                                            <option value="MUC">مونیخ</option>
                                            <option value="MLE">ماله</option>
                                            <option value="HKT">پوکت</option>
                                            <option value="GOI">گوا</option>
                                            <option value="DUS">دوسلدورف</option>
                                            <option value="DEL">دهلی</option>
                                            <option value="COK">کوچی</option>
                                            <option value="CMB">کلمبو</option>
                                            <option value="ALA">آلماتی</option>
                                            <option value="IAQ">بهرگان</option>
                                            <option value="MKU">ماکو</option>

                                        </select>
                                    </div>

                                    <div class="col-sm-2 form-group">
                                        <select data-live-search="true" id="DestinationLocation" tabindex="1" data-live-search-style="startsWith" class="selectpicker form-control" >
                                            <option id="firstOpt" value="" data-iata="" disabled="" selected="" style="visibility: hidden">مقصد</option>
                                            <option value="THR" >تهران</option>
                                            <option value="MHD">مشهد</option>
                                            <option value="KIH">کیش</option>
                                            <option value="AWZ">اهواز</option>
                                            <option value="SYZ">شیراز</option>
                                            <option value="IFN">اصفهان</option>
                                            <option value="GSM">قشم</option>
                                            <option value="TBZ">تبریز</option>
                                            <option value="ADU">اردبیل</option>
                                            <option value="BND">بندرعباس</option>
                                            <option value="OMH">ارومیه</option>
                                            <option value="QKC">کرج</option>
                                            <option value="NSH">نوشهر</option>
                                            <option value="QOM">قم</option>
                                            <option value="ABD">آبادان</option>
                                            <option value="AEU">جزیره ابوموسی</option>
                                            <option value="KER">کرمان</option>
                                            <option value="HDM">همدان</option>
                                            <option value="SRY">ساری</option>
                                            <option value="AZD">یزد</option>
                                            <option value="SDG">سنندج</option>
                                            <option value="PGU">عسلویه</option>
                                            <option value="KSH">کرمانشاه</option>
                                            <option value="AFZ">سبزوار</option>
                                            <option value="RAS">رشت</option>
                                            <option value="RZR">رامسر</option>
                                            <option value="DEF">دزفول</option>
                                            <option value="BJB">بجنورد</option>
                                            <option value="YES">یاسوج</option>
                                            <option value="KHY">خوی</option>
                                            <option value="PFQ">پارس آباد</option>
                                            <option value="BXR">بم</option>
                                            <option value="IIL">ایلام</option>
                                            <option value="KHK">خارک</option>
                                            <option value="BUZ">بوشهر</option>
                                            <option value="JWN">زنجان</option>
                                            <option value="TCX">طبس</option>
                                            <option value="GBT">گرگان</option>
                                            <option value="NJF">نجف</option>
                                            <option value="BGW">بغداد</option>
                                            <option value="IST">استانبول</option>
                                            <option value="KWI">کویت</option>
                                            <option value="KHD">خرم آباد</option>
                                            <option value="ZAH">زاهدان</option>
                                            <option value="JAR">جهرم</option>
                                            <option value="MRX">ماهشهر</option>
                                            <option value="DXB">دبی</option>
                                            <option value="ISU">سلیمانیه</option>
                                            <option value="ISE">اسپارتا</option>
                                            <option value="ZBR">چابهار</option>
                                            <option value="AYT">آنتالیا</option>
                                            <option value="TBS">تفلیس</option>
                                            <option value="ADB">ازمیر</option>
                                            <option value="KUL">کوالالامپور</option>
                                            <option value="CAN">گوانجو</option>
                                            <option value="EVN">ایروان</option>
                                            <option value="SAW">سابیها</option>
                                            <option value="BOM">بمبئی</option>
                                            <option value="ACZ">زابل</option>
                                            <option value="BSR">بصره</option>
                                            <option value="BEY">بیروت</option>
                                            <option value="XBJ">بیرجند</option>
                                            <option value="BKK">بانکوک</option>
                                            <option value="CQD">شهرکرد</option>
                                            <option value="PEK">پکن</option>
                                            <option value="EBL">اربیل</option>
                                            <option value="RJN">رفسنجان</option>
                                            <option value="GCH">گچساران</option>
                                            <option value="IHR">ایرانشهر</option>
                                            <option value="BDH">بندر لنگه</option>
                                            <option value="JYR">جیرفت</option>
                                            <option value="SYJ">سیرجان</option>
                                            <option value="AJK">اراک</option>
                                            <option value="DYU">دوشنبه</option>
                                            <option value="KBL">کابل</option>
                                            <option value="LRR">لار</option>
                                            <option value="ACP">مراغه</option>
                                            <option value="RUD">شاهرود</option>
                                            <option value="BAK">باکو</option>
                                            <option value="ASF">آستراخان</option>
                                            <option value="BUS">باتومی</option>
                                            <option value="MOW">مسکو</option>
                                            <option value="IEV">کی یف</option>
                                            <option value="ANK">آنکارا</option>
                                            <option value="KSN">کاشان</option>
                                            <option value="ADA">آدنا</option>
                                            <option value="LFM">لامرد</option>
                                            <option value="MCT">مسقط</option>
                                            <option value="TSE">آستانه</option>
                                            <option value="AMS">آمستردام</option>
                                            <option value="BAH">بحرین</option>
                                            <option value="CGN">کلن</option>
                                            <option value="CPH">کپنهاگ</option>
                                            <option value="DAM">دمشق</option>
                                            <option value="DOH">دوحه</option>
                                            <option value="ROM">رم</option>
                                            <option value="FRA">فرانکفورت</option>
                                            <option value="GOT">گوتنبرگ</option>
                                            <option value="HAM">هامبورگ</option>
                                            <option value="KHI">کراچی</option>
                                            <option value="MIL">میلان</option>
                                            <option value="PAR">پاریس</option>
                                            <option value="STO">استکهلم</option>
                                            <option value="TAS">تاشکند</option>
                                            <option value="VIE">وین</option>
                                            <option value="VIL">داخله</option>
                                            <option value="LED">سنت پترزبورگ</option>
                                            <option value="VAR">وارنا</option>
                                            <option value="LON">لندن</option>
                                            <option value="DNZ">دنیزلی</option>
                                            <option value="AKW">امیدیه</option>
                                            <option value="PVG">شانگهای</option>
                                            <option value="MUC">مونیخ</option>
                                            <option value="MLE">ماله</option>
                                            <option value="HKT">پوکت</option>
                                            <option value="GOI">گوا</option>
                                            <option value="DUS">دوسلدورف</option>
                                            <option value="DEL">دهلی</option>
                                            <option value="COK">کوچی</option>
                                            <option value="CMB">کلمبو</option>
                                            <option value="ALA">آلماتی</option>
                                            <option value="IAQ">بهرگان</option>
                                            <option value="MKU">ماکو</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2 form-group">
                                        <select data-live-search="true" id="adult" tabindex="3" id="originSelect" data-live-search-style="startsWith" class="selectpicker form-control" >
                                            <option id="firstOpt" value="1" data-iata="" disabled="" selected="" >12 سال به بالا</option>
                                            <option value="1" >1 بزرگسال</option>
                                            <option value="2">2 بزرگسال</option>
                                            <option value="3">3 بزرگسال</option>
                                            <option value="4">4 بزرگسال</option>
                                            <option value="5">5 بزرگسال</option>
                                            <option value="6">6 بزرگسال</option>
                                            <option value="7">7 بزرگسال</option>
                                            <option value="8">8 بزرگسال</option>
                                            <option value="9">9 بزرگسال</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2 form-group">
                                        <select data-live-search="true" id="child" tabindex="4" id="originSelect" data-live-search-style="startsWith" class="selectpicker form-control" >
                                            <option id="firstOpt" value="0" data-iata="" disabled="" selected="" >2 تا 12 سال</option>
                                            <option value="0">0 کودک</option>
                                            <option value="1">1 کودک</option>
                                            <option value="2">2 کودک</option>
                                            <option value="3">3 کودک</option>
                                            <option value="4">4 کودک</option>
                                            <option value="5">5 کودک</option>
                                            <option value="6">6 کودک</option>
                                            <option value="7">7 کودک</option>
                                            <option value="8">8 کودک</option>
                                            <option value="9">9 کودک</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2 form-group">
                                        <select data-live-search="true" id="baby" tabindex="5" id="originSelect" data-live-search-style="startsWith" class="selectpicker form-control" >
                                            <option id="firstOpt" value="0" data-iata="" disabled="" selected="" >0 تا 2 سال</option>
                                            <option value="0">0 نوزاد</option>
                                            <option value="1">1 نوزاد</option>
                                            <option value="2">2 نوزاد</option>
                                            <option value="3">3 نوزاد</option>
                                            <option value="4">4 نوزاد</option>
                                            <option value="5">5 نوزاد</option>
                                            <option value="6">6 نوزاد</option>
                                            <option value="7">7 نوزاد</option>
                                            <option value="8">8 نوزاد</option>
                                            <option value="9">9 نوزاد</option>
                                        </select>

                                    </div>
                                    <div class="col-sm-3 form-group ">
                                        <button tabindex="6" id="search" type="submit" class="form-control btn btn-danger" >جستجو</button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>
                    {{--result from search--}}

                    <div id="result">
                        <div class="row rounded" style="padding: 20px;background: white;margin-left: 20px;border-radius: 5px;min-height: 500px;border: solid #000;border-width: 1px;margin-top: 20px">
                            <div class="col-sm-12" id="contentResult" style="visibility: visible;margin-top: 10px;margin-bottom:10px;min-height: auto">
                                <div id="divError" style="visibility: hidden"></div>
                                <div id="divContent" style="visibility: hidden">
                                    <div id="div1" class="col-sm-2 col-xs-6">
                                        <h5 id="ch1"></h5>
                                        <br>
                                        <h5 id="ch11"></h5>

                                    </div>
                                    <div id="div2" class="col-sm-2 col-xs-6">
                                        <h5 id="ch2">زمان حرکت</h5>
                                        <br>
                                        <h5 id="ch22"></h5>
                                    </div>
                                    <div id="div3" class="col-sm-2 col-xs-6">
                                        <h5 id="ch3">زمان رسیدن</h5>
                                        <br>
                                        <h5 id="ch33"></h5>
                                    </div>
                                    <div id="div4" class="col-sm-2 col-xs-6">
                                        <h5 id="ch4">ظرفیت</h5>
                                        <br>
                                        <h5 id="ch44"></h5>
                                    </div>
                                    <div id="div5" class="col-sm-2 col-xs-6">
                                        <h5 id="ch5">نوع بلیت</h5>
                                        <br>
                                        <h5 id="ch55"></h5>
                                    </div>
                                    <button id="buy" style="margin-top: 30px" class="btn btn-success col-sm-2 col-xs-12">خرید</button>

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















