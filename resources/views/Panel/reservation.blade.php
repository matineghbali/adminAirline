@include('Section.Header')

            <script>
                $(document).ready(function () {
                    var ADTNumber= {{$data['ADTNumber']}};
                    var CHDNumber= {{$data['CHDNumber']}};
                    var INFNumber= {{$data['INFNumber']}};

                    if (CHDNumber>0){
                        $("#ADT").after($("#ADT").clone().attr('id','CHD'));
                        $("#CHD .h4Passenger").text('اطلاعات مسافران (کودک)');
                        $("#CHD .passengerBody").attr('id','passengerBodyCHD0')

                    }

                    if (INFNumber>0) {
                        $("#CHD").after($("#ADT").clone().attr('id', 'INF'));
                        $("#INF .h4Passenger").text('اطلاعات مسافران (نوزاد)');
                        $("#INF .passengerBody").attr('id','passengerBodyINF0')

                    }

                    for (i=1;i<ADTNumber;i++){
                        $("#ADT").append($('#passengerBodyADT0').clone().attr('id','passengerBodyADT'+i));
                        if (i!= ADTNumber-1)
                            $('#passengerBodyADT'+i).after('<hr>');
                    }



                    for (i=1;i<CHDNumber;i++){
                        $("#CHD").append($('#passengerBodyCHD0').clone().attr('id','passengerBodyCHD'+i));
                        if (i!= CHDNumber-1)
                            $('#passengerBodyCHD'+i).after('<hr>');

                    }

                    for (i=1;i<INFNumber;i++){
                        $("#INF").append($('#passengerBodyINF0').clone().attr('id','passengerBodyINF'+i));
                        if (i!= CHDNumber-1)
                            $('#passengerBodyINF'+i).after('<hr>');

                    }


                });
            </script>

            adt:{{$data['ADTNumber']}}
            chd:{{$data['CHDNumber']}}
            inf:{{$data['INFNumber']}}
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-12" >
                            <div class="panelTitle">اطلاعات بلیط {{$data['DepartureAirport']}} به {{$data['ArrivalAirport']}} {{$data['DepartureDate']}}</div>

                            <div class="panel">
                                <div class="row panelContent" >
                                    <div class="col-md-3">
                                        <h3>{{$data['DepartureTime']}}</h3>
                                        <span>{{$data['DepartureAirport']}}  {{$data['ArrivalAirport']}}</span>
                                    </div>
                                    <div class="col-md-2" style="padding-top: 20px">
                                        <span class="text-muted" >هواپیمایی {{$data['MarketingAirline']}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <ul>
                                            <li>هواپیما: <b>{{$data['AirEquipType']}} </b></li>
                                            <li>شماره پرواز: <b>{{$data['FlightNumber']}}</b></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul>
                                            <li>پرواز  <b>چارتر </b></li>
                                            <li>کلاس پروازی: <b>{{$data['cabinType']}}</b></li>
                                        </ul>

                                    </div>
                                    <div class="col-md-3">
                                        <h3>{{$data['passengerNumber']}} نفر </h3>
                                        <span>{{$data['price']}} تومان</span>
                                    </div>

                                </div>
                            </div>

                            <form method="post" action="{{route('reserve')}}">
                                {{csrf_field()}}
                                <div class="formContent">
                                    {{--customer info--}}
                                    <div class="customerInfo">
                                        <div class="row" >
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام و نام خانوادگی</label>
                                                    <input class="form-control" type="text" name="customer-name" value="{{old('customer-name')}}">
                                                    @if($errors->has('customer-name'))
                                                        <div class="invalid-feedback">
                                                            <small class="text-danger">{{$errors->first('customer-name')}}</small>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="email1" class="formLabel">ایمیل</label>
                                                    <input type="text" class="form-control" id="email" aria-describedby="emailHelp" name="email" value="{{old('email')}}">
                                                    @if($errors->has('email'))
                                                        <div class="invalid-feedback">
                                                            <small class="text-danger">{{$errors->first('email')}}</small>
                                                        </div>
                                                    @else
                                                        <small id="emailHelp" class="form-text text-muted">پس از خرید، بلیط به ایمیل شما ارسال می گردد.</small>
                                                    @endif

                                                </div>

                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="tel" class="formLabel">شماره تماس</label>
                                                    <input type="tel" class="form-control" id="tel" aria-describedby="telHelp" name="tel" value="{{old('tel')}}">
                                                    @if($errors->has('tel'))
                                                        <div class="invalid-feedback">
                                                            <small class="text-danger">{{$errors->first('tel')}}</small>
                                                        </div>
                                                    @else
                                                        <small id="telHelp" class="form-text text-muted">مثال: ۰۹۱۲۱۲۳۴۵۶۷</small>
                                                    @endif

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
                                <div class="addFieldContent">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-7 m-passengers__section addFieldLabel">
                                            مشخصات مسافران را وارد کنید:
                                        </div>
                                        <div class="col-md-6 col-lg-5 addField">
                                            <button class="btn add-passenger m-passengers__addp addFieldBtn" data-age="0">
                                                <span class="addFieldSpan" >
                                                    <svg class="addFieldSVG"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg>
                                                </span>
                                                بزرگسال
                                            </button>
                                            <button class="btn add-passenger m-passengers__addp addFieldBtn" data-age="0">
                                                <span class="addFieldSpan" >
                                                    <svg class="addFieldSVG"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg>
                                                </span>
                                                کودک
                                            </button>
                                            <button class="btn add-passenger m-passengers__addp addFieldBtn" data-age="0">
                                                <span class="addFieldSpan" >
                                                    <svg class="addFieldSVG"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><polygon points="42,20 22,20 22,0 20,0 20,20 0,20 0,22 20,22 20,42 22,42 22,22 42,22"></polygon></svg>
                                                </span>
                                                نوزاد
                                            </button>

                                        </div>
                                    </div>

                                </div>

                                {{--adult info--}}
                                <div class="passengerContent" id="ADT">
                                    <div class="passengerHeader">
                                        <h4 class="h4Passenger">
                                            اطلاعات مسافران (بزرگسال)
                                        </h4>
                                    </div>

                                    <div class="passengerBody" id="passengerBodyADT0">
                                        <div class="row">
                                            <div class="passengerPastPassenger">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-th-list"></i> مسافران سابق</button>
                                                <button class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                            </div>

                                        </div>
                                        <div class="row passengerInfo">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="sex" class="formLabel">جنسیت</label>
                                                    <select class="form-control" id="sex" name="sex" value="{{old('sex')}}">
                                                        <option {{old('sex')=="select" ? 'selected' : ''}} value="select">انتخاب</option>
                                                        <option {{old('sex')=="female" ? 'selected' : ''}} value="female">زن</option>
                                                        <option {{old('sex')=="male" ? 'selected' : ''}} value="male">مرد</option>
                                                    </select>
                                                    @if($errors->has('sex'))
                                                        <div class="invalid-feedback">
                                                            <small class="text-danger">{{$errors->first('sex')}}</small>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام</label>
                                                    <input class="form-control" type="text" name="passenger-fname" value="{{old('passenger-fname')}}">
                                                    @if($errors->has('passenger-fname'))
                                                        <div class="invalid-feedback">
                                                            <small class="text-danger">{{$errors->first('passenger-fname')}}</small>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام خانوادگی</label>
                                                    <input class="form-control" type="text" name="passenger-lname" value="{{old('passenger-lname')}}">
                                                    @if($errors->has('passenger-lname'))
                                                        <div class="invalid-feedback">
                                                            <small class="text-danger">{{$errors->first('passenger-lname')}}</small>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">کد ملی</label>
                                                    <input class="form-control" type="text" name="passenger-id" value="{{old('passenger-id')}}">
                                                    @if($errors->has('passenger-id'))
                                                        <div class="invalid-feedback">
                                                            <small class="text-danger">{{$errors->first('passenger-id')}}</small>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">تاریخ تولد</label>
                                                    <input class="form-control" type="text" name="passenger-birthday" value="{{old('passenger-birthday')}}">
                                                    @if($errors->has('passenger-birthday'))
                                                        <div class="invalid-feedback">
                                                            <small class="text-danger">{{$errors->first('passenger-birthday')}}</small>
                                                        </div>
                                                    @else
                                                        <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <hr>

                                </div>


                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-6">
                                        <div class="passengerBtn">
                                            <button class="btn btn-primary btn-block" type="submit">ثبت اطلاعات</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /. ROW  -->

@include('Section.Footer')