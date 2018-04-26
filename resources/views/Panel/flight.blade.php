            @include('Section.Header')
            <div class="row">
                <div class="col-sm-12">
                    <div class="searchBoxContent">
                        <form id="form">
                            {{csrf_field()}}

                            <div class="row">
                                <div class="col-sm-2 form-group">
                                    <input type="text"  id="datepicker" class="form-control"  placeholder="تاریخ پرواز" >
                                </div>
                                <div class="col-sm-2 form-group">
                                    <select data-live-search="true" id="OriginLocation" tabindex="0" data-live-search-style="startsWith" class="selectpicker form-control" >
                                        <option id="firstOpt" value="" data-iata=""  disabled selected=""  style="visibility: hidden">مبدأ</option>
                                        <option value="THR" >تهران</option>
                                        <option value="MHD">مشهد</option>
                                        <option value="KIH">کیش</option>
                                        <option value="AWZ">اهواز</option>
                                        <option value="SYZ">شیراز</option>
                                        <option value="IFN">اصفهان</option>
                                        <option value="GSM">قشم</option>
                                        <option value="TBZ">تبریز</option>
                                        <option value="ADU">اردبیل</option>
                                        <option value="BND">بندرعباس</option>
                                        <option value="OMH">ارومیه</option>
                                        <option value="QKC">کرج</option>
                                        <option value="NSH">نوشهر</option>
                                        <option value="QOM">قم</option>
                                        <option value="ABD">آبادان</option>
                                        <option value="AEU">جزیره ابوموسی</option>
                                        <option value="KER">کرمان</option>
                                        <option value="HDM">همدان</option>
                                        <option value="SRY">ساری</option>
                                        <option value="AZD">یزد</option>
                                        <option value="SDG">سنندج</option>
                                        <option value="PGU">عسلویه</option>
                                        <option value="KSH">کرمانشاه</option>
                                        <option value="AFZ">سبزوار</option>
                                        <option value="RAS">رشت</option>
                                        <option value="RZR">رامسر</option>
                                        <option value="DEF">دزفول</option>
                                        <option value="BJB">بجنورد</option>
                                        <option value="YES">یاسوج</option>
                                        <option value="KHY">خوی</option>
                                        <option value="PFQ">پارس آباد</option>
                                        <option value="BXR">بم</option>
                                        <option value="IIL">ایلام</option>
                                        <option value="KHK">خارک</option>
                                        <option value="BUZ">بوشهر</option>
                                        <option value="JWN">زنجان</option>
                                        <option value="TCX">طبس</option>
                                        <option value="GBT">گرگان</option>
                                        <option value="NJF">نجف</option>
                                        <option value="BGW">بغداد</option>
                                        <option value="IST">استانبول</option>
                                        <option value="KWI">کویت</option>
                                        <option value="KHD">خرم آباد</option>
                                        <option value="ZAH">زاهدان</option>
                                        <option value="JAR">جهرم</option>
                                        <option value="MRX">ماهشهر</option>
                                        <option value="DXB">دبی</option>
                                        <option value="ISU">سلیمانیه</option>
                                        <option value="ISE">اسپارتا</option>
                                        <option value="ZBR">چابهار</option>
                                        <option value="AYT">آنتالیا</option>
                                        <option value="TBS">تفلیس</option>
                                        <option value="ADB">ازمیر</option>
                                        <option value="KUL">کوالالامپور</option>
                                        <option value="CAN">گوانجو</option>
                                        <option value="EVN">ایروان</option>
                                        <option value="SAW">سابیها</option>
                                        <option value="BOM">بمبئی</option>
                                        <option value="ACZ">زابل</option>
                                        <option value="BSR">بصره</option>
                                        <option value="BEY">بیروت</option>
                                        <option value="XBJ">بیرجند</option>
                                        <option value="BKK">بانکوک</option>
                                        <option value="CQD">شهرکرد</option>
                                        <option value="PEK">پکن</option>
                                        <option value="EBL">اربیل</option>
                                        <option value="RJN">رفسنجان</option>
                                        <option value="GCH">گچساران</option>
                                        <option value="IHR">ایرانشهر</option>
                                        <option value="BDH">بندر لنگه</option>
                                        <option value="JYR">جیرفت</option>
                                        <option value="SYJ">سیرجان</option>
                                        <option value="AJK">اراک</option>
                                        <option value="DYU">دوشنبه</option>
                                        <option value="KBL">کابل</option>
                                        <option value="LRR">لار</option>
                                        <option value="ACP">مراغه</option>
                                        <option value="RUD">شاهرود</option>
                                        <option value="BAK">باکو</option>
                                        <option value="ASF">آستراخان</option>
                                        <option value="BUS">باتومی</option>
                                        <option value="MOW">مسکو</option>
                                        <option value="IEV">کی یف</option>
                                        <option value="ANK">آنکارا</option>
                                        <option value="KSN">کاشان</option>
                                        <option value="ADA">آدنا</option>
                                        <option value="LFM">لامرد</option>
                                        <option value="MCT">مسقط</option>
                                        <option value="TSE">آستانه</option>
                                        <option value="AMS">آمستردام</option>
                                        <option value="BAH">بحرین</option>
                                        <option value="CGN">کلن</option>
                                        <option value="CPH">کپنهاگ</option>
                                        <option value="DAM">دمشق</option>
                                        <option value="DOH">دوحه</option>
                                        <option value="ROM">رم</option>
                                        <option value="FRA">فرانکفورت</option>
                                        <option value="GOT">گوتنبرگ</option>
                                        <option value="HAM">هامبورگ</option>
                                        <option value="KHI">کراچی</option>
                                        <option value="MIL">میلان</option>
                                        <option value="PAR">پاریس</option>
                                        <option value="STO">استکهلم</option>
                                        <option value="TAS">تاشکند</option>
                                        <option value="VIE">وین</option>
                                        <option value="VIL">داخله</option>
                                        <option value="LED">سنت پترزبورگ</option>
                                        <option value="VAR">وارنا</option>
                                        <option value="LON">لندن</option>
                                        <option value="DNZ">دنیزلی</option>
                                        <option value="AKW">امیدیه</option>
                                        <option value="PVG">شانگهای</option>
                                        <option value="MUC">مونیخ</option>
                                        <option value="MLE">ماله</option>
                                        <option value="HKT">پوکت</option>
                                        <option value="GOI">گوا</option>
                                        <option value="DUS">دوسلدورف</option>
                                        <option value="DEL">دهلی</option>
                                        <option value="COK">کوچی</option>
                                        <option value="CMB">کلمبو</option>
                                        <option value="ALA">آلماتی</option>
                                        <option value="IAQ">بهرگان</option>
                                        <option value="MKU">ماکو</option>

                                    </select>
                                </div>

                                <div class="col-sm-2 form-group">
                                    <select data-live-search="true" id="DestinationLocation" tabindex="1" data-live-search-style="startsWith" class="selectpicker form-control" >
                                        <option id="firstOpt" value="" data-iata="" disabled="" selected="" style="visibility: hidden">مقصد</option>
                                        <option value="THR" >تهران</option>
                                        <option value="MHD">مشهد</option>
                                        <option value="KIH">کیش</option>
                                        <option value="AWZ">اهواز</option>
                                        <option value="SYZ">شیراز</option>
                                        <option value="IFN">اصفهان</option>
                                        <option value="GSM">قشم</option>
                                        <option value="TBZ">تبریز</option>
                                        <option value="ADU">اردبیل</option>
                                        <option value="BND">بندرعباس</option>
                                        <option value="OMH">ارومیه</option>
                                        <option value="QKC">کرج</option>
                                        <option value="NSH">نوشهر</option>
                                        <option value="QOM">قم</option>
                                        <option value="ABD">آبادان</option>
                                        <option value="AEU">جزیره ابوموسی</option>
                                        <option value="KER">کرمان</option>
                                        <option value="HDM">همدان</option>
                                        <option value="SRY">ساری</option>
                                        <option value="AZD">یزد</option>
                                        <option value="SDG">سنندج</option>
                                        <option value="PGU">عسلویه</option>
                                        <option value="KSH">کرمانشاه</option>
                                        <option value="AFZ">سبزوار</option>
                                        <option value="RAS">رشت</option>
                                        <option value="RZR">رامسر</option>
                                        <option value="DEF">دزفول</option>
                                        <option value="BJB">بجنورد</option>
                                        <option value="YES">یاسوج</option>
                                        <option value="KHY">خوی</option>
                                        <option value="PFQ">پارس آباد</option>
                                        <option value="BXR">بم</option>
                                        <option value="IIL">ایلام</option>
                                        <option value="KHK">خارک</option>
                                        <option value="BUZ">بوشهر</option>
                                        <option value="JWN">زنجان</option>
                                        <option value="TCX">طبس</option>
                                        <option value="GBT">گرگان</option>
                                        <option value="NJF">نجف</option>
                                        <option value="BGW">بغداد</option>
                                        <option value="IST">استانبول</option>
                                        <option value="KWI">کویت</option>
                                        <option value="KHD">خرم آباد</option>
                                        <option value="ZAH">زاهدان</option>
                                        <option value="JAR">جهرم</option>
                                        <option value="MRX">ماهشهر</option>
                                        <option value="DXB">دبی</option>
                                        <option value="ISU">سلیمانیه</option>
                                        <option value="ISE">اسپارتا</option>
                                        <option value="ZBR">چابهار</option>
                                        <option value="AYT">آنتالیا</option>
                                        <option value="TBS">تفلیس</option>
                                        <option value="ADB">ازمیر</option>
                                        <option value="KUL">کوالالامپور</option>
                                        <option value="CAN">گوانجو</option>
                                        <option value="EVN">ایروان</option>
                                        <option value="SAW">سابیها</option>
                                        <option value="BOM">بمبئی</option>
                                        <option value="ACZ">زابل</option>
                                        <option value="BSR">بصره</option>
                                        <option value="BEY">بیروت</option>
                                        <option value="XBJ">بیرجند</option>
                                        <option value="BKK">بانکوک</option>
                                        <option value="CQD">شهرکرد</option>
                                        <option value="PEK">پکن</option>
                                        <option value="EBL">اربیل</option>
                                        <option value="RJN">رفسنجان</option>
                                        <option value="GCH">گچساران</option>
                                        <option value="IHR">ایرانشهر</option>
                                        <option value="BDH">بندر لنگه</option>
                                        <option value="JYR">جیرفت</option>
                                        <option value="SYJ">سیرجان</option>
                                        <option value="AJK">اراک</option>
                                        <option value="DYU">دوشنبه</option>
                                        <option value="KBL">کابل</option>
                                        <option value="LRR">لار</option>
                                        <option value="ACP">مراغه</option>
                                        <option value="RUD">شاهرود</option>
                                        <option value="BAK">باکو</option>
                                        <option value="ASF">آستراخان</option>
                                        <option value="BUS">باتومی</option>
                                        <option value="MOW">مسکو</option>
                                        <option value="IEV">کی یف</option>
                                        <option value="ANK">آنکارا</option>
                                        <option value="KSN">کاشان</option>
                                        <option value="ADA">آدنا</option>
                                        <option value="LFM">لامرد</option>
                                        <option value="MCT">مسقط</option>
                                        <option value="TSE">آستانه</option>
                                        <option value="AMS">آمستردام</option>
                                        <option value="BAH">بحرین</option>
                                        <option value="CGN">کلن</option>
                                        <option value="CPH">کپنهاگ</option>
                                        <option value="DAM">دمشق</option>
                                        <option value="DOH">دوحه</option>
                                        <option value="ROM">رم</option>
                                        <option value="FRA">فرانکفورت</option>
                                        <option value="GOT">گوتنبرگ</option>
                                        <option value="HAM">هامبورگ</option>
                                        <option value="KHI">کراچی</option>
                                        <option value="MIL">میلان</option>
                                        <option value="PAR">پاریس</option>
                                        <option value="STO">استکهلم</option>
                                        <option value="TAS">تاشکند</option>
                                        <option value="VIE">وین</option>
                                        <option value="VIL">داخله</option>
                                        <option value="LED">سنت پترزبورگ</option>
                                        <option value="VAR">وارنا</option>
                                        <option value="LON">لندن</option>
                                        <option value="DNZ">دنیزلی</option>
                                        <option value="AKW">امیدیه</option>
                                        <option value="PVG">شانگهای</option>
                                        <option value="MUC">مونیخ</option>
                                        <option value="MLE">ماله</option>
                                        <option value="HKT">پوکت</option>
                                        <option value="GOI">گوا</option>
                                        <option value="DUS">دوسلدورف</option>
                                        <option value="DEL">دهلی</option>
                                        <option value="COK">کوچی</option>
                                        <option value="CMB">کلمبو</option>
                                        <option value="ALA">آلماتی</option>
                                        <option value="IAQ">بهرگان</option>
                                        <option value="MKU">ماکو</option>
                                    </select>
                                </div>

                                <div class="col-sm-2 form-group">
                                    <select data-live-search="true" id="ADT" tabindex="3" id="originSelect" data-live-search-style="startsWith" class="selectpicker form-control" >
                                        {{--<option id="firstOpt" value="1" data-iata="" disabled="" selected="" >12 سال به بالا</option>--}}
                                        <option value="1" selected>1 بزرگسال</option>
                                        <option value="2">2 بزرگسال</option>
                                        <option value="3">3 بزرگسال</option>
                                        <option value="4">4 بزرگسال</option>
                                        <option value="5">5 بزرگسال</option>
                                        <option value="6">6 بزرگسال</option>
                                        <option value="7">7 بزرگسال</option>
                                        <option value="8">8 بزرگسال</option>
                                        <option value="9">9 بزرگسال</option>
                                    </select>
                                </div>

                                <div class="col-sm-2 form-group">
                                    <select data-live-search="true" id="CHD" tabindex="4" id="originSelect" data-live-search-style="startsWith" class="selectpicker form-control" >
                                        {{--<option id="firstOpt" value="0" data-iata="" disabled="" selected="" >2 تا 12 سال</option>--}}
                                        <option value="0" selected>0 کودک</option>
                                        <option value="1">1 کودک</option>
                                        <option value="2">2 کودک</option>
                                        <option value="3">3 کودک</option>
                                        <option value="4">4 کودک</option>
                                        <option value="5">5 کودک</option>
                                        <option value="6">6 کودک</option>
                                        <option value="7">7 کودک</option>
                                        <option value="8">8 کودک</option>
                                        <option value="9">9 کودک</option>
                                    </select>
                                </div>

                                <div class="col-sm-2 form-group">
                                    <select data-live-search="true" id="INF" tabindex="5" id="originSelect" data-live-search-style="startsWith" class="selectpicker form-control" >
                                        {{--<option id="firstOpt" value="0" data-iata=""  selected="" >0 تا 2 سال</option>--}}
                                        <option value="0" selected>0 نوزاد</option>
                                        <option value="1">1 نوزاد</option>
                                        <option value="2">2 نوزاد</option>
                                        <option value="3">3 نوزاد</option>
                                        <option value="4">4 نوزاد</option>
                                        <option value="5">5 نوزاد</option>
                                        <option value="6">6 نوزاد</option>
                                        <option value="7">7 نوزاد</option>
                                        <option value="8">8 نوزاد</option>
                                        <option value="9">9 نوزاد</option>
                                    </select>

                                </div>

                                <div class="col-sm-3 form-group ">
                                    <button tabindex="6" id="search" type="submit" class="form-control btn btn-danger" >جستجو</button>

                                </div>

                            </div>

                        </form>
                    </div>
                    {{--result from search--}}

                    <div class="searchResult">
                        <div id="contentResult">
                        </div>
                    </div>




                </div>
            </div>

            <script>
                $(document).ready(function () {


                    $('#datepicker').persianDatepicker({
                        startDate: 'today',
                        endDate: '1400/2/2'
                    });


                    $('#form').on('submit',function (e) {
                        e.preventDefault();

                        var _token=$('input[name="_token"]').val();

                        var formData=new  FormData();
                        formData.append('OriginLocation',$('#OriginLocation').val());
                        formData.append('DestinationLocation',$('#DestinationLocation').val());
                        formData.append('DepartureDateTime',$('#datepicker').val());
                        formData.append('ADT',$('#ADT').val());
                        formData.append('CHD',$('#CHD').val());
                        formData.append('INF',$('#INF').val());
                        $.ajax({
                            method: 'POST',
                            url: '/admin/getFlight2',
                            data: formData,
                            contentType : false,
                            processData: false,
                            headers: {
                                'X_CSRF-TOKEN': _token
                            },

                        }).done(function (data) {
                            console.log(data);
                            $.ajax({
                                method: 'get',
                                url: '/admin/getFlight3',
                                data: formData,
                                contentType : false,
                                processData: false,

                            }).done(function (response) {
                                console.log(response);
                                $('#contentResult').attr('style','visibility:visible');
                                $('#contentResult').html(response['html']);
                                if (response['date'] != 'false')
                                    $('#datepicker').val(response['date']);


                            });//end function of ajax2

                        });//end function of ajax1

                    })//end form submit
                })//end jquery
            </script>
            @include('Section.Footer')