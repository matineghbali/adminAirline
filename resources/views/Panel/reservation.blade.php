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
                                    <a  href="{{route('adminPanel')}}"><i class="fa fa-dashboard fa-3x"></i> میزکار</a>
                                </li>
                                <li>
                                    <a    href="{{route('getFlight')}}"><i class="fa fa-desktop fa-3x"></i> جستجوی پرواز</a>
                                </li>
                                <li>
                                    <a class="active-menu"  href="#"><i class="fa fa-qrcode fa-3x"></i>رزرو بلیط</a>
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

                <div class="row">
                    <div class="col-sm-12" >
                        <div style="text-align: center;font-weight: 300;line-height: 1;margin: 0 0 20px 0;">اطلاعات بلیط تهران به مشهد سه شنبه ۴ اردیبهشت ۱۳۹۷</div>

                        <div style="border:1px solid #ddd;margin: 0 0 10px 0;background: white">
                            <div class="row" style="padding: 15px;text-align: center;line-height: 50px">
                                <div class="col-md-3">
                                    <h3>۲۱:۰۰</h3>
                                    <span>تهران  مشهد</span>
                                </div>
                                <div class="col-md-2" style="padding-top: 20px">
                                    <span class="text-muted" >هواپیمایی تابان ایر</span>
                                </div>
                                <div class="col-md-2">
                                    <ul>
                                        <li>هواپیما: <b>بوئینگ </b></li>
                                        <li>شماره پرواز: <b>6224</b></li>
                                    </ul>
                                </div>
                                <div class="col-md-2">
                                    <ul>
                                        <li>پرواز  <b>چارتر </b></li>
                                        <li>کلاس پروازی: <b>اکونومی</b></li>
                                    </ul>

                                </div>
                                <div class="col-md-3">
                                    <h3>١ نفر </h3>
                                    <span>٢٤٨,٠٠٠ تومان</span>
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
