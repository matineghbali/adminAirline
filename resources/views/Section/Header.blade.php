<?php
require_once __DIR__ . '/../../../app/Http/Function/funnction.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>پنل مدیریت</title>

    <link rel="stylesheet" href="/assets/css/fontiran.css">
    <link href="/assets/css/bootstrap.css" rel="stylesheet" />

    <link rel="stylesheet" href="/assets/css/bootstrapValidator.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">


    <link href="/assets/css/bootstrap-rtl.min.css" rel="stylesheet" />
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/css/custom.css" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="/assets/css/persianDatepicker-default.css" />


    {{--persianDatepicker--}}
    <script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="/assets/js/persianDatepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>


    {{--js for toggleButton--}}
    <script src="/assets/js/bootstrap.min.js"></script>
    {{--<script src="/assets/js/jquery.metisMenu.js"></script>--}}
    {{--<script src="/assets/js/custom.js"></script>--}}
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>


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
                    <a   href="{{route('getFlight')}}" ><i class="fa fa-desktop fa-3x"></i>بلیت هواپیما</a>
                </li>
                <li>
                    <a   href="{{route('getPassenger')}}" ><i class="fa fa-desktop fa-3x"></i>لیست مسافران</a>
                </li>

            </ul>

        </div>

    </nav>
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper" >
        <div id="page-inner">