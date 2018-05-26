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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="/assets/css/bootstrapValidator.css">
    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrapValidator.js"></script>

    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>


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
                    <a href="{{route('getFlight')}}" ><i class="fa fa-desktop fa-3x"></i>بلیت هواپیما</a>
                </li>
                <li>
                    <a   href="{{route('getPassenger')}}" ><i class="fa fa-desktop fa-3x"></i>لیست مسافران</a>
                </li>

            </ul>

        </div>

    </nav>




    <div id="page-wrapper" >
        <div id="page-inner">
            <div id="registerPage" >
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="col-sm-12" >
                                <form method="post" action="{{route('UpdatePassenger',['id'=>$passenger->id])}}">
                                    {{csrf_field()}}
                                    {{method_field('PATCH')}}
                                    <div class="passengerContent">
                                        <div class="passengerHeader">
                                            <h4 class="h4Passenger">
                                                تغییر اطلاعات مسافر
                                            </h4>
                                        </div>

                                        <div class="passengerBody">
                                            <div class="row passengerInfo">
                                                <input type="hidden" value="ADT" name="type" class="PassengerType">


                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="sex" class="formLabel">جنسیت</label>
                                                        <select class="form-control passenger-gender" name="gender" required>
                                                            <option value="">انتخاب</option>
                                                            <option value="0" {{$passenger->gender==0 ? 'selected' : ''}}>زن</option>
                                                            <option value="1" {{$passenger->gender==1 ? 'selected' : ''}}>مرد</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="customer-name" class="formLabel">نام</label>
                                                        <input class="form-control" type="text" name="fname" value="{{$passenger->fname}}">

                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="customer-name" class="formLabel">نام خانوادگی</label>
                                                        <input class="form-control" type="text" name="lname" value="{{$passenger->lname}}">

                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="customer-name" class="formLabel">کد ملی</label>
                                                        <input class="form-control" type="text" name="doc_id" value="{{$passenger->doc_id}}">

                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="customer-name" class="formLabel" >تاریخ تولد</label>
                                                        <input class="form-control datepicker"  type="text" name="birthday" value="{{$passenger->birthday}}"
                                                               readonly style="background-color: white;cursor: context-menu" >
                                                        <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        {{--submit --}}
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="passengerBtn" >
                                                            <a href="{{route('getPassenger')}}" style="text-decoration:none;">
                                                                <button class="btn btn-block btn-success  btnSubmit" >
                                                                    بازگشت
                                                                </button>
                                                            </a>
                                                        </div>

                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="passengerBtn" >
                                                            <button class="btn btn-block btn-primary  btnSubmit" type="submit">
                                                                تغییر
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>




                            </div>

                        </div>
                    </div>
                </div>

            </div>



            <div id="reservePage" >

            </div>


        </div>
    </div>
</div>

<script>
    $(' .datepicker').persianDatepicker({
        cellWidth: 50,
        cellHeight: 30,
        fontSize: 18
    });
</script>



</body>
</html>
