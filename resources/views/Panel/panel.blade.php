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
        $('document').ready(function () {
            $('#b').click(function () {

                $('#divv').attr('style','visibility:visible')
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
                {{--<div class="row " style="background: #5F5D5D;margin-left: 20px;min-height:80px;border-radius: 8px">--}}
                    {{--<h2 style="padding: 10px">--}}
                        {{--{{\Illuminate\Support\Facades\Auth::user()->name}} عزیز خوش آمدید!--}}

                    {{--</h2>--}}

                {{--</div>--}}

                <button id="b">jvgv</button>

                <div id="result" style="visibility: hidden">
                    <div class="row" id="row" style="background: white;margin-left: 20px;min-height:80px;border-radius: 8px;padding: 5px">
                        <div id="div1" class="col-sm-2 col-xs-6">
                            <h4 id="ch1">شرکت هواپیمایی</h4>
                            <br>
                            <h4 id="ch11">شماره پرواز</h4>

                        </div>
                        <div id="div2" class="col-sm-2 col-xs-6">
                            <h4 id="ch2">زمان حرکت</h4>
                            <br>
                            <h4 id="ch22">۰۳ اردیبهشت، ۱۳۹۷ ۱۳:۰۰:۰۰	</h4>
                        </div>
                        <div id="div3" class="col-sm-2 col-xs-6">
                            <h4 id="ch3">زمان رسیدن</h4>
                            <br>
                            <h4 id="ch33">۰۳ اردیبهشت، ۱۳۹۷ ۱۳:۰۰:۰۰	</h4>
                        </div>
                        <div id="div4" class="col-sm-2 col-xs-6">
                            <h4 id="ch4">ظرفیت</h4>
                            <br>
                            <h4 id="ch44">X نفر</h4>
                        </div>
                        <div id="div5" class="col-sm-2 col-xs-6">
                            <h4 id="ch5">نوع بلیت</h4>
                            <br>
                            <h4 id="ch55">X تومان</h4>
                        </div>
                        <button id="buy" style="margin-top: 50px" class="btn btn-success col-sm-1 col-xs-12">خرید</button>
                    </div>

                </div>




            </div>


        </div>
    </div>

</div>








</body>
</html>



</body>
</html>
