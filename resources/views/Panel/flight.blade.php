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
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-select.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>



    <script>
        $(document).ready(function () {
            $('#form').on('submit',function (e) {
                // $("#table").style.visibility="hidden";

                e.preventDefault();

                var OriginLocation=$('#OriginLocation').val();
                var DestinationLocation=$('#DestinationLocation').val();
                var DepartureDateTime=$('#DepartureDateTime').val();
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
                // console.log(formData);
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
                    var i=0;
                    for(i in data['PricedItineraries']){
                        $("td:eq( "+i+" )").text(data['PricedItineraries'][i]['AirItinerary']['OriginDestinationOptions']
                            [0]['FlightSegment'][0]['MarketingAirline']['Value']);

                    }
                   // alert(data['PricedItineraries'][0]['AirItinerary']['OriginDestinationOptions'][0]['FlightSegment'][0]['ArrivalAirport']['LocationCode']);

                });

            })
        })
    </script>

</head>
<body>
<div id="wrapper"  style="background-color: white">
    <nav class="navbar navbar-default navbar-cls-top row" role="navigation" style="margin-bottom: 0">
        <div class="col-sm-3">
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
        <div class="col-sm-9">
            <div style="color: white;
            padding: 15px 50px 5px 50px;
            float: left;
            font-size: 16px;" > {{jdate()->format('%B %d، %Y')}} &nbsp; <a href="/logout" class="btn btn-danger square-btn-adjust">خروج</a>
            </div>

        </div>
    </nav>


        <div class="row">
            <div class="col-sm-3"  >
                <div class="navbar-default navbar-side col-sm-12" role="navigation" >

                    <div class="sidebar-collapse"  >
                        <ul class="nav" id="main-menu">
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

                </div>
            </div>

            {{--<div class="container">--}}
                <div class="col-sm-9" id="content" >
                    <form class="form-inline" id="form">
                        {{csrf_field()}}
                        <input type="text" tabindex="2" id="DepartureDateTime" class="form-control mb-2 col-sm-2" id="inlineFormInputName2" placeholder="تاریخ پرواز">

                        <select data-live-search="true" id="OriginLocation" tabindex="0" id="originSelect" data-live-search-style="startsWith" class="form-control mb-2 col-sm-2 selectpicker" >
                            <option id="firstOpt" value="" data-iata="" disabled="" selected="" >مبدأ را مشخص کنید</option>
                            <option value="THR" selected>تهران</option>
                            <option value="MHD">مشهد</option>
                            <option value="3" data-iata="KIH">کیش</option>
                            <option value="2" data-iata="AWZ">اهواز</option>
                            <option value="10" data-iata="SYZ">شیراز</option>
                            <option value="32" data-iata="IFN">اصفهان</option>
                            <option value="4" data-iata="GSM">قشم</option>
                            <option value="25" data-iata="TBZ">تبریز</option>
                            <option value="12" data-iata="ADU">اردبیل</option>
                            <option value="33" data-iata="BND">بندرعباس</option>
                            <option value="16" data-iata="OMH">ارومیه</option>
                            <option value="5" data-iata="QKC">کرج</option>
                            <option value="6" data-iata="NSH">نوشهر</option>
                            <option value="7" data-iata="QOM">قم</option>
                            <option value="8" data-iata="ABD">آبادان</option>
                            <option value="9" data-iata="AEU">جزیره ابوموسی</option>
                            <option value="11" data-iata="KER">کرمان</option>
                            <option value="13" data-iata="HDM">همدان</option>
                            <option value="15" data-iata="SRY">ساری</option>
                            <option value="17" data-iata="AZD">یزد</option>
                            <option value="18" data-iata="SDG">سنندج</option>
                            <option value="19" data-iata="PGU">عسلویه</option>
                            <option value="20" data-iata="KSH">کرمانشاه</option>
                            <option value="21" data-iata="AFZ">سبزوار</option>
                            <option value="22" data-iata="RAS">رشت</option>
                            <option value="23" data-iata="RZR">رامسر</option>
                            <option value="24" data-iata="DEF">دزفول</option>
                            <option value="26" data-iata="BJB">بجنورد</option>
                            <option value="27" data-iata="YES">یاسوج</option>
                            <option value="28" data-iata="KHY">خوی</option>
                            <option value="29" data-iata="PFQ">پارس آباد</option>
                            <option value="30" data-iata="BXR">بم</option>
                            <option value="34" data-iata="IIL">ایلام</option>
                            <option value="35" data-iata="KHK">خارک</option>
                            <option value="36" data-iata="BUZ">بوشهر</option>
                            <option value="37" data-iata="JWN">زنجان</option>
                            <option value="38" data-iata="TCX">طبس</option>
                            <option value="39" data-iata="GBT">گرگان</option>
                            <option value="40" data-iata="NJF">نجف</option>
                            <option value="41" data-iata="BGW">بغداد</option>
                            <option value="42" data-iata="IST">استانبول</option>
                            <option value="43" data-iata="KWI">کویت</option>
                            <option value="44" data-iata="KHD">خرم آباد</option>
                            <option value="45" data-iata="ZAH">زاهدان</option>
                            <option value="1168" data-iata="JAR">جهرم</option>
                            <option value="46" data-iata="MRX">ماهشهر</option>
                            <option value="47" data-iata="DXB">دبی</option>
                            <option value="48" data-iata="ISU">سلیمانیه</option>
                            <option value="49" data-iata="ISE">اسپارتا</option>
                            <option value="50" data-iata="ZBR">چابهار</option>
                            <option value="51" data-iata="AYT">آنتالیا</option>
                            <option value="52" data-iata="TBS">تفلیس</option>
                            <option value="53" data-iata="ADB">ازمیر</option>
                            <option value="54" data-iata="KUL">کوالالامپور</option>
                            <option value="55" data-iata="CAN">گوانجو</option>
                            <option value="56" data-iata="EVN">ایروان</option>
                            <option value="57" data-iata="SAW">سابیها</option>
                            <option value="58" data-iata="BOM">بمبئی</option>
                            <option value="59" data-iata="ACZ">زابل</option>
                            <option value="60" data-iata="BSR">بصره</option>
                            <option value="61" data-iata="BEY">بیروت</option>
                            <option value="1065" data-iata="XBJ">بیرجند</option>
                            <option value="1066" data-iata="BKK">بانکوک</option>
                            <option value="1076" data-iata="CQD">شهرکرد</option>
                            <option value="1077" data-iata="PEK">پکن</option>
                            <option value="1078" data-iata="EBL">اربیل</option>
                            <option value="1080" data-iata="RJN">رفسنجان</option>
                            <option value="1081" data-iata="GCH">گچساران</option>
                            <option value="1082" data-iata="IHR">ایرانشهر</option>
                            <option value="1085" data-iata="BDH">بندر لنگه</option>
                            <option value="1087" data-iata="JYR">جیرفت</option>
                            <option value="1092" data-iata="SYJ">سیرجان</option>
                            <option value="1093" data-iata="AJK">اراک</option>
                            <option value="1094" data-iata="DYU">دوشنبه</option>
                            <option value="1095" data-iata="KBL">کابل</option>
                            <option value="1096" data-iata="LRR">لار</option>
                            <option value="1097" data-iata="ACP">مراغه</option>
                            <option value="1098" data-iata="RUD">شاهرود</option>
                            <option value="1099" data-iata="BAK">باکو</option>
                            <option value="1100" data-iata="ASF">آستراخان</option>
                            <option value="1101" data-iata="BUS">باتومی</option>
                            <option value="1102" data-iata="MOW">مسکو</option>
                            <option value="1103" data-iata="IEV">کی یف</option>
                            <option value="1104" data-iata="ANK">آنکارا</option>
                            <option value="1106" data-iata="KSN">کاشان</option>
                            <option value="1107" data-iata="ADA">آدنا</option>
                            <option value="1108" data-iata="LFM">لامرد</option>
                            <option value="1109" data-iata="MCT">مسقط</option>
                            <option value="1110" data-iata="TSE">آستانه</option>
                            <option value="1111" data-iata="AMS">آمستردام</option>
                            <option value="1112" data-iata="BAH">بحرین</option>
                            <option value="1114" data-iata="CGN">کلن</option>
                            <option value="1115" data-iata="CPH">کپنهاگ</option>
                            <option value="1116" data-iata="DAM">دمشق</option>
                            <option value="1117" data-iata="DOH">دوحه</option>
                            <option value="1118" data-iata="ROM">رم</option>
                            <option value="1119" data-iata="FRA">فرانکفورت</option>
                            <option value="1120" data-iata="GOT">گوتنبرگ</option>
                            <option value="1121" data-iata="HAM">هامبورگ</option>
                            <option value="1122" data-iata="KHI">کراچی</option>
                            <option value="1123" data-iata="MIL">میلان</option>
                            <option value="1124" data-iata="PAR">پاریس</option>
                            <option value="1125" data-iata="STO">استکهلم</option>
                            <option value="1126" data-iata="TAS">تاشکند</option>
                            <option value="1127" data-iata="VIE">وین</option>
                            <option value="1128" data-iata="VIL">داخله</option>
                            <option value="1129" data-iata="LED">سنت پترزبورگ</option>
                            <option value="1130" data-iata="VAR">وارنا</option>
                            <option value="1131" data-iata="LON">لندن</option>
                            <option value="1132" data-iata="DNZ">دنیزلی</option>
                            <option value="1137" data-iata="AKW">امیدیه</option>
                            <option value="1149" data-iata="PVG">شانگهای</option>
                            <option value="1148" data-iata="MUC">مونیخ</option>
                            <option value="1147" data-iata="MLE">ماله</option>
                            <option value="1145" data-iata="HKT">پوکت</option>
                            <option value="1144" data-iata="GOI">گوا</option>
                            <option value="1143" data-iata="DUS">دوسلدورف</option>
                            <option value="1142" data-iata="DEL">دهلی</option>
                            <option value="1141" data-iata="COK">کوچی</option>
                            <option value="1140" data-iata="CMB">کلمبو</option>
                            <option value="1139" data-iata="ALA">آلماتی</option>
                            <option value="1150" data-iata="IAQ">بهرگان</option>
                            <option value="1154" data-iata="MKU">ماکو</option>
                        </select>


                        <select data-live-search="true" id="DestinationLocation" tabindex="1" id="originSelect" data-live-search-style="startsWith" class="form-control mb-2 col-sm-2 selectpicker  pt-24" >
                            <option id="firstOpt" value="" data-iata="" disabled="" selected="" >مقصد را مشخص کنید</option>
                            <option value="THR">تهران</option>
                            <option value="MHD" selected>مشهد</option>
                            <option value="3" data-iata="KIH">کیش</option>
                            <option value="2" data-iata="AWZ">اهواز</option>
                            <option value="10" data-iata="SYZ">شیراز</option>
                            <option value="32" data-iata="IFN">اصفهان</option>
                            <option value="4" data-iata="GSM">قشم</option>
                            <option value="25" data-iata="TBZ">تبریز</option>
                            <option value="12" data-iata="ADU">اردبیل</option>
                            <option value="33" data-iata="BND">بندرعباس</option>
                            <option value="16" data-iata="OMH">ارومیه</option>
                            <option value="5" data-iata="QKC">کرج</option>
                            <option value="6" data-iata="NSH">نوشهر</option>
                            <option value="7" data-iata="QOM">قم</option>
                            <option value="8" data-iata="ABD">آبادان</option>
                            <option value="9" data-iata="AEU">جزیره ابوموسی</option>
                            <option value="11" data-iata="KER">کرمان</option>
                            <option value="13" data-iata="HDM">همدان</option>
                            <option value="15" data-iata="SRY">ساری</option>
                            <option value="17" data-iata="AZD">یزد</option>
                            <option value="18" data-iata="SDG">سنندج</option>
                            <option value="19" data-iata="PGU">عسلویه</option>
                            <option value="20" data-iata="KSH">کرمانشاه</option>
                            <option value="21" data-iata="AFZ">سبزوار</option>
                            <option value="22" data-iata="RAS">رشت</option>
                            <option value="23" data-iata="RZR">رامسر</option>
                            <option value="24" data-iata="DEF">دزفول</option>
                            <option value="26" data-iata="BJB">بجنورد</option>
                            <option value="27" data-iata="YES">یاسوج</option>
                            <option value="28" data-iata="KHY">خوی</option>
                            <option value="29" data-iata="PFQ">پارس آباد</option>
                            <option value="30" data-iata="BXR">بم</option>
                            <option value="34" data-iata="IIL">ایلام</option>
                            <option value="35" data-iata="KHK">خارک</option>
                            <option value="36" data-iata="BUZ">بوشهر</option>
                            <option value="37" data-iata="JWN">زنجان</option>
                            <option value="38" data-iata="TCX">طبس</option>
                            <option value="39" data-iata="GBT">گرگان</option>
                            <option value="40" data-iata="NJF">نجف</option>
                            <option value="41" data-iata="BGW">بغداد</option>
                            <option value="42" data-iata="IST">استانبول</option>
                            <option value="43" data-iata="KWI">کویت</option>
                            <option value="44" data-iata="KHD">خرم آباد</option>
                            <option value="45" data-iata="ZAH">زاهدان</option>
                            <option value="1168" data-iata="JAR">جهرم</option>
                            <option value="46" data-iata="MRX">ماهشهر</option>
                            <option value="47" data-iata="DXB">دبی</option>
                            <option value="48" data-iata="ISU">سلیمانیه</option>
                            <option value="49" data-iata="ISE">اسپارتا</option>
                            <option value="50" data-iata="ZBR">چابهار</option>
                            <option value="51" data-iata="AYT">آنتالیا</option>
                            <option value="52" data-iata="TBS">تفلیس</option>
                            <option value="53" data-iata="ADB">ازمیر</option>
                            <option value="54" data-iata="KUL">کوالالامپور</option>
                            <option value="55" data-iata="CAN">گوانجو</option>
                            <option value="56" data-iata="EVN">ایروان</option>
                            <option value="57" data-iata="SAW">سابیها</option>
                            <option value="58" data-iata="BOM">بمبئی</option>
                            <option value="59" data-iata="ACZ">زابل</option>
                            <option value="60" data-iata="BSR">بصره</option>
                            <option value="61" data-iata="BEY">بیروت</option>
                            <option value="1065" data-iata="XBJ">بیرجند</option>
                            <option value="1066" data-iata="BKK">بانکوک</option>
                            <option value="1076" data-iata="CQD">شهرکرد</option>
                            <option value="1077" data-iata="PEK">پکن</option>
                            <option value="1078" data-iata="EBL">اربیل</option>
                            <option value="1080" data-iata="RJN">رفسنجان</option>
                            <option value="1081" data-iata="GCH">گچساران</option>
                            <option value="1082" data-iata="IHR">ایرانشهر</option>
                            <option value="1085" data-iata="BDH">بندر لنگه</option>
                            <option value="1087" data-iata="JYR">جیرفت</option>
                            <option value="1092" data-iata="SYJ">سیرجان</option>
                            <option value="1093" data-iata="AJK">اراک</option>
                            <option value="1094" data-iata="DYU">دوشنبه</option>
                            <option value="1095" data-iata="KBL">کابل</option>
                            <option value="1096" data-iata="LRR">لار</option>
                            <option value="1097" data-iata="ACP">مراغه</option>
                            <option value="1098" data-iata="RUD">شاهرود</option>
                            <option value="1099" data-iata="BAK">باکو</option>
                            <option value="1100" data-iata="ASF">آستراخان</option>
                            <option value="1101" data-iata="BUS">باتومی</option>
                            <option value="1102" data-iata="MOW">مسکو</option>
                            <option value="1103" data-iata="IEV">کی یف</option>
                            <option value="1104" data-iata="ANK">آنکارا</option>
                            <option value="1106" data-iata="KSN">کاشان</option>
                            <option value="1107" data-iata="ADA">آدنا</option>
                            <option value="1108" data-iata="LFM">لامرد</option>
                            <option value="1109" data-iata="MCT">مسقط</option>
                            <option value="1110" data-iata="TSE">آستانه</option>
                            <option value="1111" data-iata="AMS">آمستردام</option>
                            <option value="1112" data-iata="BAH">بحرین</option>
                            <option value="1114" data-iata="CGN">کلن</option>
                            <option value="1115" data-iata="CPH">کپنهاگ</option>
                            <option value="1116" data-iata="DAM">دمشق</option>
                            <option value="1117" data-iata="DOH">دوحه</option>
                            <option value="1118" data-iata="ROM">رم</option>
                            <option value="1119" data-iata="FRA">فرانکفورت</option>
                            <option value="1120" data-iata="GOT">گوتنبرگ</option>
                            <option value="1121" data-iata="HAM">هامبورگ</option>
                            <option value="1122" data-iata="KHI">کراچی</option>
                            <option value="1123" data-iata="MIL">میلان</option>
                            <option value="1124" data-iata="PAR">پاریس</option>
                            <option value="1125" data-iata="STO">استکهلم</option>
                            <option value="1126" data-iata="TAS">تاشکند</option>
                            <option value="1127" data-iata="VIE">وین</option>
                            <option value="1128" data-iata="VIL">داخله</option>
                            <option value="1129" data-iata="LED">سنت پترزبورگ</option>
                            <option value="1130" data-iata="VAR">وارنا</option>
                            <option value="1131" data-iata="LON">لندن</option>
                            <option value="1132" data-iata="DNZ">دنیزلی</option>
                            <option value="1137" data-iata="AKW">امیدیه</option>
                            <option value="1149" data-iata="PVG">شانگهای</option>
                            <option value="1148" data-iata="MUC">مونیخ</option>
                            <option value="1147" data-iata="MLE">ماله</option>
                            <option value="1145" data-iata="HKT">پوکت</option>
                            <option value="1144" data-iata="GOI">گوا</option>
                            <option value="1143" data-iata="DUS">دوسلدورف</option>
                            <option value="1142" data-iata="DEL">دهلی</option>
                            <option value="1141" data-iata="COK">کوچی</option>
                            <option value="1140" data-iata="CMB">کلمبو</option>
                            <option value="1139" data-iata="ALA">آلماتی</option>
                            <option value="1150" data-iata="IAQ">بهرگان</option>
                            <option value="1154" data-iata="MKU">ماکو</option>
                        </select>


                        <select data-live-search="true" id="adult" tabindex="3" id="originSelect" data-live-search-style="startsWith" class="form-control mb-2 col-sm-1 selectpicker" >
                                    <option id="firstOpt" value="" data-iata="" disabled="" selected="" >12 سال به بالا</option>
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

                        <select data-live-search="true" id="child" tabindex="4" id="originSelect" data-live-search-style="startsWith" class="form-control mb-2 col-sm-1 selectpicker" >
                                    <option id="firstOpt" value="" data-iata="" disabled="" selected="" >2 تا 12 سال</option>
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

                        <select data-live-search="true" id="baby" tabindex="5" id="originSelect" data-live-search-style="startsWith" class="form-control mb-2 col-sm-1 selectpicker" >
                                    <option id="firstOpt" value="" data-iata="" disabled="" selected="" >0 تا 2 سال</option>
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


                        <button tabindex="6" id="search" type="submit" class="btn btn-danger mb-2">جستجو</button>
                    </form>




                {{--</div>--}}

                <div id="result" style="max-height:600px;margin-top: 100px">
                    <table id="table" class="table" style="visibility: visible">
                        <thead>
                        <tr>
                            <th scope="col">شماره ستون</th>
                            <th scope="col">شرکت هواپیمایی</th>
                            <th scope="col">شماره پرواز</th>
                            <th scope="col">زمان حرکت</th>
                            <th scope="col">زمان رسیدن به مقصد</th>
                            <th scope="col">ظرفیت</th>
                            <th scope="col">نوع بلیط</th>
                        </tr>
                        </thead>


                        <tr id="trContent">
                            <td id="1"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>

                </div>

            </div>

        </div>

</div>

<script src="/assets/js/jquery-1.10.2.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/jquery.metisMenu.js"></script>
<script src="/assets/js/custom.js"></script>


</body>
</html>
