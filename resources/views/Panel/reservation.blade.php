@include('Section.Header')
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-12" >
                            <div class="panelTitle">اطلاعات بلیط تهران به مشهد سه شنبه ۴ اردیبهشت ۱۳۹۷</div>

                            <div class="panel">
                                <div class="row panelContent" >
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
                                <div class="formContent">
                                    {{--customer info--}}
                                    <div class="customerInfo">
                                        <div class="row" >
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="customer-name" class="formLabel">نام و نام خانوادگی</label>
                                                    <input class="form-control" type="text" name="customer-name">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="email1" class="formLabel">ایمیل</label>
                                                    <input type="email" class="form-control" id="email1" aria-describedby="emailHelp">
                                                    <small id="emailHelp" class="form-text text-muted">پس از خرید، بلیط به ایمیل شما ارسال می گردد.</small>
                                                </div>

                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="tel" class="formLabel">شماره تماس</label>
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
                                <div class="passengerContent">
                                    <div class="passengerHeader">
                                        <h4>
                                            اطلاعات مسافران (بزرگسال)
                                        </h4>
                                    </div>

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
                                                <select class="form-control" id="sex">
                                                    <option selected>انتخاب</option>
                                                    <option value="1">زن</option>
                                                    <option value="2">مرد</option>
                                                </select>                                        </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">نام</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">نام خانوادگی</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">کد ملی</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">تاریخ تولد</label>
                                                <input class="form-control" type="text" name="customer-name">
                                                <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{--child info--}}
                                <div class="passengerContent">
                                    <div class="passengerHeader">
                                        <h4>
                                            اطلاعات مسافران (کودک)
                                        </h4>
                                    </div>

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
                                                <select class="form-control" id="sex">
                                                    <option selected>انتخاب</option>
                                                    <option value="1">زن</option>
                                                    <option value="2">مرد</option>
                                                </select>                                        </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">نام</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">نام خانوادگی</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">کد ملی</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">تاریخ تولد</label>
                                                <input class="form-control" type="text" name="customer-name">
                                                <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{--inf info--}}
                                <div class="passengerContent">
                                    <div class="passengerHeader">
                                        <h4>
                                            اطلاعات مسافران (نوزاد)
                                        </h4>
                                    </div>

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
                                                <select class="form-control" id="sex">
                                                    <option selected>انتخاب</option>
                                                    <option value="1">زن</option>
                                                    <option value="2">مرد</option>
                                                </select>                                        </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">نام</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">نام خانوادگی</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">کد ملی</label>
                                                <input class="form-control" type="text" name="customer-name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="customer-name" class="formLabel">تاریخ تولد</label>
                                                <input class="form-control" type="text" name="customer-name">
                                                <small id="telHelp" class="form-text text-muted">مثال: ۱۳۹۱/۰۲/۰۶</small>

                                            </div>
                                        </div>
                                    </div>

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