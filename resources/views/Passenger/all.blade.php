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
    <link rel="stylesheet" href="/assets/css/bootstrapValidator.css">
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>

    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrapValidator.js"></script>

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
                    <a   href="{{route('getFlight')}}" ><i class="fa fa-plane fa-3x"></i> بلیت هواپیما</a>
                </li>
                <li>
                    <a   href="{{route('getPassenger')}}" ><i class="fa fa-user fa-3x"></i> لیست مسافران</a>
                </li>

            </ul>

        </div>

    </nav>
    <!-- /. NAV SIDE  -->


    <div id="page-wrapper" >
        <div id="page-inner">
            @include('sweetalert::alert')
            @if(count($passengers)==0)
                <div class="alert alert-danger">هیچ مسافری وجود ندارد.</div>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12" >
                                {{--Passenger info--}}
                                <div class="passengerContent" style="margin-top: 30px">
                                    <div class="passengerHeader">
                                        <h4 class="h4Passenger">
                                            اطلاعات مسافران
                                        </h4>
                                    </div>
                                    <div class="passengerBody" >
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr class="small">
                                                    <th>#</th>
                                                    <th>نوع</th>
                                                    <th>جنسیت</th>
                                                    <th>نام و نام خانوادگی</th>
                                                    <th>کد ملی</th>
                                                    <th>تاریخ تولد</th>
                                                    <th>قیمت بلیت</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php $i=0 ?>
                                                @foreach($passengers as $passenger)
                                                    <tr>
                                                        <td>
                                                            {{toPersianNum(++$i)}}
                                                        </td>
                                                        <td>
                                                            @if($passenger->type=='ADT')
                                                                بزرگسال
                                                            @elseif($passenger->type=='CHD')
                                                                کودک
                                                            @else
                                                                نوزاد
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($passenger->gender==0)
                                                                <b>خانم</b>
                                                            @else
                                                                <b>آقا</b>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{$passenger->fname.' '. $passenger->lname }}
                                                        </td>
                                                        <td class="nowrap">
                                                            <strong>
                                                                {{toPersianNum($passenger->doc_id)}}
                                                            </strong>
                                                        </td>
                                                        <td>
                                                            {{toPersianNum($passenger->birthday)}}
                                                        </td>
                                                        <td>
                                                            {{toPersianNum($passenger->price)}}
                                                        </td>
                                                        <td>
                                                            <form method="post" action="{{route('DeletePassenger',['id'=>$passenger->id])}}">
                                                            <a href="{{route('EditPassenger',['id'=>$passenger->id])}}" class="btn-sm btn-success" style="text-decoration:none;">تغییر</a>
                                                                {{csrf_field()}}
                                                                {{method_field('delete')}}
                                                                <button type="submit" class="btn-sm btn-danger" style="text-decoration:none;"> حذف</button>
                                                            <a href="{{route('getTicket',['id'=>$passenger->id])}}" class="btn-sm btn-primary" style="text-decoration:none;">مشاهده بلیت ها</a>
                                                            </form>

                                                        </td>
                                                    </tr>

                                                @endforeach


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>



                    </div>
                </div>
            @endif

            <div style="text-align: center">
                {!! $passengers->render() !!}
            </div>
        </div>
    </div>
</div>

</body>
</html>
