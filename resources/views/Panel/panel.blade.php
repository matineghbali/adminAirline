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
        // $(document).ready(function(){
        //     $("#originSelect").hover(function(){
        //         // $("#firstOpt").text('tehran');
        //         alert('iuuik');
        //     });
        // });
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
                            <a class="active-menu"   href="{{route('adminPanel')}}"><i class="fa fa-dashboard fa-3x"></i> میزکار</a>
                        </li>
                        <li>
                            <a   href="{{route('getFlight')}}"><i class="fa fa-desktop fa-3x"></i> جستجوی پرواز</a>
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
            <h2>
                {{\Illuminate\Support\Facades\Auth::user()->name}} عزیز خوش آمدید!

            </h2>

        </div>

    </div>

</div>

<script src="/assets/js/jquery-1.10.2.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/jquery.metisMenu.js"></script>
<script src="/assets/js/custom.js"></script>


</body>
</html>
