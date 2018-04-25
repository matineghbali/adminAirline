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

                        <form>
                            <div style="border: 1px solid #ddd;border-radius: 3px;margin-top: 20px;background: #f4f4f4">
                                {{--customer info--}}
                                <div style="padding: 15px">
                                    <div class="row" >
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" style="font-size: 12px;color: #666;">نام و نام خانوادگی</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email1" style="font-size: 12px;color: #666;">ایمیل</label>
                                                <input type="email" class="form-control" id="email1" aria-describedby="emailHelp">
                                                <small id="emailHelp" class="form-text text-muted">پس از خرید، بلیط به ایمیل شما ارسال می گردد.</small>
                                            </div>

                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="tel" style="font-size: 12px;color: #666;">شماره تماس</label>
                                                <input type="tel" class="form-control" id="tel" aria-describedby="telHelp">
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
                            <div style="margin-top: 20px;padding: 15px">
                                <div class="row">
                                    <div class="col-md-6 col-lg-7 m-passengers__section" style="padding: 10px 15px;background-color: #FFF;border-radius: 6px;border: 1px solid #ededed;margin-bottom: 10px;">
                                        مشخصات مسافران را وارد کنید
                                    </div>
                                    <div class="col-md-6 col-lg-5" style="padding: 0 6px 0 0;text-align: center;">
                                        <button class="btn add-passenger m-passengers__addp" data-age="0" style="position: relative;
    padding: 0;
    box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.20);
    background-color: #FFF;
    border-radius: 4px;
    border: 1px solid #ededed;
    height: 43px;
    width: 32%;
    margin: 0 2px 0 0 !important;
    padding-left: 38px;">
                                        <span style="display: block;
    position: absolute;
    left: 0;
    top: 0;
    width: 42px;
    height: 42px;
    border-right: 1px solid #e2e2e2;">
                                            <svg style="width: 16px;
    height: 16px;
    margin: 8.5px;
    fill: #397ff6;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg></span>
                                            بزرگسال
                                        </button>
                                        <button class="btn add-passenger m-passengers__addp" data-age="0" style="position: relative;
    padding: 0;
    box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.20);
    background-color: #FFF;
    border-radius: 4px;
    border: 1px solid #ededed;
    height: 43px;
    width: 32%;
    margin: 0 2px 0 0 !important;
    padding-left: 38px;">
                                        <span style="display: block;
    position: absolute;
    left: 0;
    top: 0;
    width: 42px;
    height: 42px;
    border-right: 1px solid #e2e2e2;">
                                            <svg style="width: 16px;
    height: 16px;
    margin: 8.5px;
    fill: #397ff6;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg></span>
                                            کودک
                                        </button>
                                        <button class="btn add-passenger m-passengers__addp" data-age="0" style="position: relative;
    padding: 0;
    box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.20);
    background-color: #FFF;
    border-radius: 4px;
    border: 1px solid #ededed;
    height: 43px;
    width: 32%;
    margin: 0 2px 0 0 !important;
    padding-left: 38px;">
                                        <span style="display: block;
    position: absolute;
    left: 0;
    top: 0;
    width: 42px;
    height: 42px;
    border-right: 1px solid #e2e2e2;">
                                            <svg style="width: 16px;
    height: 16px;
    margin: 8.5px;
    fill: #397ff6;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg></span>
                                            نوزاد
                                        </button>
                                    </div>
                                </div>

                            </div>

                            {{--adult info--}}
                            <div style="border: 1px solid #d6e9c6;border-radius: 3px;background: white;margin-top: 15px">
                                <div style="background: #d6e9c6;border-bottom: 1px solid #d6e9c6">
                                    <h4 style="padding: 10px;font-size: 13px;margin: 0px">
                                        اطلاعات مسافران (بزرگسال)
                                    </h4>
                                </div>

                                <div class="row">
                                    <div style="float: left;margin-top: 10px;padding-left: 20px">
                                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-th-list"></i> مسافران سابق</button>
                                        <button class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                    </div>

                                </div>
                                <div class="row" style="padding: 10px">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="sex" style="font-size: 12px;color: #666;">جنسیت</label>
                                            <select class="form-control" id="sex">
                                                <option selected>انتخاب</option>
                                                <option value="1">زن</option>
                                                <option value="2">مرد</option>
                                            </select>                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">نام</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">نام خانوادگی</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">کد ملی</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">تاریخ تولد</label>
                                            <input class="form-control" type="text" name="customer-name">
                                            <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{--child info--}}
                            <div style="border: 1px solid #d6e9c6;border-radius: 3px;background: white;margin-top: 15px">
                                <div style="background: #d6e9c6;border-bottom: 1px solid #d6e9c6">
                                    <h4 style="padding: 10px;font-size: 13px;margin: 0px">
                                        اطلاعات مسافران (بزرگسال)
                                    </h4>
                                </div>

                                <div class="row">
                                    <div style="float: left;margin-top: 10px;padding-left: 20px">
                                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-th-list"></i> مسافران سابق</button>
                                        <button class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                    </div>

                                </div>
                                <div class="row" style="padding: 10px">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="sex" style="font-size: 12px;color: #666;">جنسیت</label>
                                            <select class="form-control" id="sex">
                                                <option selected>انتخاب</option>
                                                <option value="1">زن</option>
                                                <option value="2">مرد</option>
                                            </select>                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">نام</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">نام خانوادگی</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">کد ملی</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">تاریخ تولد</label>
                                            <input class="form-control" type="text" name="customer-name">
                                            <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{--inf info--}}
                            <div style="border: 1px solid #d6e9c6;border-radius: 3px;background: white;margin-top: 15px">
                                <div style="background: #d6e9c6;border-bottom: 1px solid #d6e9c6">
                                    <h4 style="padding: 10px;font-size: 13px;margin: 0px">
                                        اطلاعات مسافران (بزرگسال)
                                    </h4>
                                </div>

                                <div class="row">
                                    <div style="float: left;margin-top: 10px;padding-left: 20px">
                                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-th-list"></i> مسافران سابق</button>
                                        <button class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                    </div>

                                </div>
                                <div class="row" style="padding: 10px">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="sex" style="font-size: 12px;color: #666;">جنسیت</label>
                                            <select class="form-control" id="sex">
                                                <option selected>انتخاب</option>
                                                <option value="1">زن</option>
                                                <option value="2">مرد</option>
                                            </select>                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">نام</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">نام خانوادگی</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">کد ملی</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">تاریخ تولد</label>
                                            <input class="form-control" type="text" name="customer-name">
                                            <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{--inf info--}}
                            <div style="border: 1px solid #d6e9c6;border-radius: 3px;background: white;margin-top: 15px">
                                <div style="background: #d6e9c6;border-bottom: 1px solid #d6e9c6">
                                    <h4 style="padding: 10px;font-size: 13px;margin: 0px">
                                        اطلاعات مسافران (بزرگسال)
                                    </h4>
                                </div>

                                <div class="row">
                                    <div style="float: left;margin-top: 10px;padding-left: 20px">
                                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-th-list"></i> مسافران سابق</button>
                                        <button class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                    </div>

                                </div>
                                <div class="row" style="padding: 10px">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="sex" style="font-size: 12px;color: #666;">جنسیت</label>
                                            <select class="form-control" id="sex">
                                                <option selected>انتخاب</option>
                                                <option value="1">زن</option>
                                                <option value="2">مرد</option>
                                            </select>                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">نام</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">نام خانوادگی</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">کد ملی</label>
                                            <input class="form-control" type="text" name="customer-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="customer-name" style="font-size: 12px;color: #666;">تاریخ تولد</label>
                                            <input class="form-control" type="text" name="customer-name">
                                            <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <div style="margin-top: 20px;margin-bottom:20px;">
                                        <button class="btn btn-primary btn-block" type="submit">ثبت اطلاعات</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>

            </div>


        </div>
    </div>

</div>

</body>
</html>
