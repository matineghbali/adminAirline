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


    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>

    <script src="/assets/js/bootstrap.min.js"></script>

    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>


    {{--persianDatepicker--}}
    {{--<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>--}}


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
                    @if(auth()->user()->image)
                        <img src="/assets/img/{{auth()->user()->image}}" class="user-image img-responsive"/>
                    @else
                        <img src="/assets/img/find_user.png" class="user-image img-responsive"/>
                    @endif
                </li>
                <li>
                    <a   href="{{route('adminPanel')}}" ><i class="fa fa-dashboard fa-3x"></i> میزکار</a>
                </li>
                <li>
                    <a   href="{{route('getFlight')}}" ><i class="fa fa-plane fa-3x"></i> بلیت هواپیما</a>
                </li>
                <li>
                    <a   href="{{route('getPassenger')}}" ><i class="fa fa-user fa-3x"></i> لیست مسافران</a>
                </li>
                <li>
                    <a   href="{{route('editProfileInfo')}}" ><i class="fa fa-edit fa-3x"></i> تغییر اطلاعات پروفایل</a>
                </li>


            </ul>

        </div>

    </nav>




    <div id="page-wrapper" >
        @include('sweetalert::alert')
        <div id="page-inner">
            <div id="registerPage" >
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            @if ($errors->any())
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-12" >

                                <form method="POST" action="{{ route('updateProfileInfo',['id'=>auth()->user()->id]) }}" enctype="multipart/form-data" >
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-group row">
                                        <label for="name" class="col-sm-offset-2 col-sm-2  col-form-label col-form-label-sm">نام</label>
                                        <div class="col-sm-6 ">
                                            <input  id="name" type="text" class="form-control form-control-sm" name="name" value="{{auth()->user()->name}}"  autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-offset-2 col-sm-2 col-form-label col-form-label-sm">{{ __('آدرس ایمیل') }}</label>
                                        <div class="col-sm-6">
                                            <input dir="rtl" id="email" type="text" class="form-control form-control-sm" name="email" value="{{auth()->user()->email}}" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-sm-offset-2 col-sm-2 col-form-label text-sm-right">{{ __('پسورد') }}</label>
                                        <div class="col-sm-6">
                                            <input dir="rtl" id="password" type="password" class="form-control" name="password">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-sm-offset-2 col-sm-2 col-form-label text-sm-right">{{ __('تکرار پسورد') }}</label>
                                        <div class="col-sm-6">
                                            <input dir="rtl" id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tel" class="col-sm-offset-2 col-sm-2 col-form-label text-sm-right">{{ __('تلفن') }}</label>
                                        <div class="col-sm-6">
                                            <input dir="rtl" id="tel" type="tel" class="form-control"
                                                  value="{{auth()->user()->tel}}" name="tel">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="image" class="col-sm-offset-2 col-sm-2 col-form-label text-sm-right">{{ __('انتخاب تصویر') }}</label>
                                        <div class="col-sm-6">
                                            <input dir="rtl" id="image" type="file" class="form-control" name="image">
                                        </div>
                                    </div>


                                    <div class="form-group row " style="margin-top: 30px">
                                        <div class="col-sm-offset-4 col-sm-4 ">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                {{ __('ثبت نام') }}
                                            </button>
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
