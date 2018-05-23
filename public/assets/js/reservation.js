$(document).ready(function() {

    var error_formValid='';
    $('#defaultForm').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            'customer-name': {
                validators: {
                    notEmpty: {
                        message: 'نام مشتری نمی تواند خالی باشد.'
                    }
                }
            },
            'email': {
                validators: {
                    notEmpty: {
                        message: 'ایمیل را وارد کنید'
                    },
                    emailAddress: {
                        message: 'آدرس ایمیل معتبر نیست'
                    }
                }
            },
            'tel': {
                validators: {
                    notEmpty: {
                        message: 'شماره تلفن را وارد کنید'
                    },
                    stringLength: {
                        min: 11,
                        max: 12,
                        message: 'شماره تلفن باید 11 رقم باشد'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'شماره تلفن را با ارقام انگلیسی وارد کنید'
                    },


                }
            },
            'passenger-gender[]': {
                validators: {
                    notEmpty: {
                        message: 'جنسیت را انتخاب کنید'
                    }
                }
            },
            'passenger-fname[]': {
                validators: {
                    notEmpty: {
                        message: 'نام مسافر را وارد کنید'
                    }
                }
            },
            'passenger-lname[]': {
                validators: {
                    notEmpty: {
                        message: 'نام خانوادگی را وارد کنید'
                    }
                }
            },
            'passenger-id[]': {
                validators: {
                    notEmpty: {
                        message: 'کد ملی را وارد کنید'
                    },

                    // stringLength: {
                    //     min: 10,
                    //     max: 10,
                    //     message: 'کد ملی باید 10 رقم باشد'
                    // },
                    // regexp: {
                    //     regexp: /^[0-9]+$/,
                    //     message: 'کد ملی را ارقام انگلیسی وارد کنید'
                    // },
                }
            },
            'passenger-birthday[]': {
                validators: {
                    notEmpty: {
                        message: 'تاریخ تولد خود را انتخاب کنید'
                    },
                    ADTError: {
                        message: 'سن بزرگسال باید بزرگتر از 12 باشد'
                    },
                    CHDError: {
                        message: 'سن کودک باید بین 2 تا 12 سال باشد'
                    },
                    INFError: {
                        message: 'سن نوزاد باید بین 7 روز تا 2 سال باشد'
                    }
                }
            },
            'type': {
                validators: {
                    notEmpty: {
                        message: 'required'
                    }
                }
            }
        }
    })
        .on('error.form.bv', function (e) {
            error_formValid='true';
            var $form = $(e.target);
        })
        .on('success.form.bv', function (e) {
            error_formValid='false';
        })
        .on('error.field.bv', function (e, data) {
            error_formValid='true';
        })
        .on('success.field.bv', function (e, data) {
            error_formValid='false';
        })
        .on('status.field.bv', function (e, data) {
            data.bv.disableSubmitButtons(false);
        });


        $('#passengerBodyADT0 .datepicker').persianDatepicker({
            cellWidth: 50,
            cellHeight: 30,
            fontSize: 18,
            onSelect: function (e) {
                getBirthday('ADT',$('#passengerBodyADT0 .datepicker').val(),$('#passengerBodyADT0 .datepicker').attr('id'));

            }

        });


    function getBirthday(passenger,date,id) {
        var _token=$('input[name="_token"]').val();
        var formData=new  FormData();
        formData.append('passenger',passenger);
        formData.append('date',date);
        $.ajax({
            method: 'POST',
            url: '/admin/getBirthday',
            data: formData,
            contentType : false,
            processData: false,
            headers: {
                'X_CSRF-TOKEN': _token
            }

        }).done(function (data) {
            console.log(data);
            if (data['status']=='invalid'){
                if (passenger=='ADT'){
                    sessionStorage.setItem('statusADTError','true');
                    sessionStorage.setItem('statusCHDError','false');
                    sessionStorage.setItem('statusINFError','false');
                }
                else if (passenger=='CHD'){
                    sessionStorage.setItem('statusADTError','false');
                    sessionStorage.setItem('statusCHDError','true');
                    sessionStorage.setItem('statusINFError','false');
                }
                else if (passenger=='INF'){
                    sessionStorage.setItem('statusADTError','false');
                    sessionStorage.setItem('statusCHDError','false');
                    sessionStorage.setItem('statusINFError','true');
                }

            }
            else {
                sessionStorage.setItem('statusADTError','false');
                sessionStorage.setItem('statusCHDError','false');
                sessionStorage.setItem('statusINFError','false');
            }
            $('#defaultForm').bootstrapValidator('revalidateField', $('#' + id));

        });
    }

    var passengerBodyADT=1;
    var passengerBodyCHD=0;
    var passengerBodyINF=0;

    function AddPassengerBody(passenger) {

        var template     = "passengerBody",
            $templateEle = $('#' + template + passenger),

            $row = $templateEle.clone().removeAttr('id').insertBefore($templateEle).removeClass('hide');
        if (passenger=="ADT")
            var passengerBody=$row.attr('id',"passengerBodyADT"+passengerBodyADT++);
        else if(passenger=='CHD')
            var passengerBody=$row.attr('id',"passengerBodyCHD"+passengerBodyCHD++);
        else if (passenger=='INF')
            var passengerBody=$row.attr('id',"passengerBodyINF"+passengerBodyINF++);


        $('#' + passenger + '.removeBTN').attr('id','remove' + passenger);


        var $el = $row.find('select').eq(0).attr('name', template + '[]');
        $('#defaultForm').bootstrapValidator('addField', $el);


        for (j = 0; j <= 4; j++) {
            var $el = $row.find('input').eq(j).attr('name', template + '[]');
            $('#defaultForm').bootstrapValidator('addField', $el);

        }

        $('#' + passengerBody.attr('id') + ' .datepicker').persianDatepicker({
                cellWidth: 50,
                cellHeight: 30,
                fontSize: 18,
                onSelect: function (e) {
                    getBirthday(passenger,$('#' + passengerBody.attr('id') + ' .datepicker').val(),$('#' + passengerBody.attr('id') + ' .datepicker').attr('id'));
                }

            })
        // });

    }


    $('.btnSubmit').click(function () {
        $('#defaultForm').bootstrapValidator('validate');
    });

    var numberOfPassengers=ADTNumber+CHDNumber+INFNumber;

    $('#defaultForm').on('submit',function (e) {
        e.preventDefault();
        $('.btnSubmit').attr('disabled', 'disabled');

        console.log(error_formValid);

        if (error_formValid == 'false'){
            nIds = [];
            $('input[data-bv-field^="passenger-id[]"]').each(function(event) {
                if ($(this).val()) nIds.push($(this).val());
            });
            nIds.sort();
            nIdsDId=false;
            for (var i = 0; i < nIds.length - 1; i++) {
                if (nIds[i + 1] == nIds[i]) {
                    nIdsDId = true;
                    tekrariid=nIds[i];
                }
            }

            if (nIdsDId){
                toastr.clear();
                toastr.error( "کد ملی " + tekrariid + " تکراریست",'' , {timeOut: 3000});
            }
            else {
                var passenger_gender=[];var i=0;

                $('.passenger-gender').each(function(e) {
                    if($(this).find(":selected").val()){
                        passenger_gender[i]=$(this).find(":selected").val();
                        i++;
                    }
                });


                var _token=$('input[name="_token"]').val();
                var formData=new  FormData();
                formData.append('customer_name',$('#customer_name').val());
                formData.append('customer_email',$('#email').val());
                formData.append('customer_tel',$('#tel').val());
                formData.append('numberOfPassengers',numberOfPassengers + '.' + ADTNumber + '.' + CHDNumber + '.' + INFNumber );
                formData.append('gender',passenger_gender);
                formData.append('fname',getFieldValue('fname'));
                formData.append('lname',getFieldValue('lname'));
                formData.append('id',getFieldValue('id'));
                formData.append('birthday',getFieldValue('birthday'));
                $.ajax({
                    method: 'POST',
                    url: '/admin/reserve',
                    data: formData,
                    contentType : false,
                    processData: false,
                    headers: {
                        'X_CSRF-TOKEN': _token
                    }

                }).done(function (data) {
                    console.log(data);
                    $('#registerPage').hide();
                    $('#reservePage').show();
                    $('#reservePage').html(data);
                    $('html, body').animate({
                        scrollTop: $("#reservePage").offset().top
                    }, 500);


                });

            }//end else

        }

    });



    // getBirthday('ADT');  //set birthday for first ADT passenger





    for(i=1;i<ADTNumber;i++){
        AddPassengerBody('ADT');
        var pastADT=0;
        $('#ADT .pastPassenger').each(function(e) {
            $(this).attr('id', 'pastPassengerADT' + pastADT++);
        });
        var datepickerADT=0;
        $('#ADT .datepicker').each(function(e) {
            $(this).attr('id', 'datepickerADT' + datepickerADT++);
        });


    }

    if (CHDNumber>0){
        $('#CHD').css("visibility", "visible");
        $('#CHD').append($('#passengerBodyADT').clone().attr('id','passengerBodyCHD'));

        $('#CHD .removeBTN').attr('id','removeCHD');


        $('#CHD .PassengerType').val('CHD');

        for(i=0;i<CHDNumber;i++)
            AddPassengerBody('CHD');

        var pastCHD=0;
        $('#CHD .pastPassenger').each(function(e) {
            $(this).attr('id', 'pastPassengerCHD' + pastCHD++);
        });

        var datepickerCHD=0;
        $('#CHD .datepicker').each(function(e) {
            $(this).attr('id', 'datepickerCHD' + datepickerCHD++);
        });


    }

    if (INFNumber>0){
        $('#INF').css("visibility", "visible");
        $('#INF').append($('#passengerBodyADT').clone().attr('id','passengerBodyINF'));
        $('#INF .removeBTN').attr('id','removeINF');
        // $('#INF .pastPassenger').attr('id','pastPassengerINF' + pastINF++ );
        $('#INF .PassengerType').val('INF');
        for(i=0;i<INFNumber;i++)
            AddPassengerBody('INF');

        var pastINF=0;
        $('#INF .pastPassenger').each(function(e) {
            $(this).attr('id', 'pastPassengerINF' + pastINF++);
        });

        var datepickerINF=0;
        $('#INF .datepicker').each(function(e) {
            $(this).attr('id', 'datepickerINF' + datepickerINF++);
        });


    }

    var PassengerNumERR='تعداد مسافرها نمی تواند بیشتر از 9 باشد، درصورت نیاز تعداد بیشتر جداگانه صادر کنید';
    var PassengerINFERR='تعداد نوزاد نمی تواند بیشتر از بزرگسال باشد';

    $('.addADT').on('click', function () {
        if (numberOfPassengers == 9){
            toastr.clear();
            toastr.error(PassengerNumERR,'',{timeOut:3000});
        }
        else{
            ADTNumber++;
            numberOfPassengers++;
            AddPassengerBody('ADT');
            var pastADT=0;
            $('#ADT .pastPassenger').each(function(e) {
                $(this).attr('id', 'pastPassengerADT' + pastADT++);
            });
            var datepickerADT=0;
            $('#ADT .datepicker').each(function(e) {
                $(this).attr('id', 'datepickerADT' + datepickerADT++);
            });


        }


    });

    $('.addCHD').on('click', function () {

        if (numberOfPassengers == 9){
            toastr.clear();
            toastr.error(PassengerNumERR,'',{timeOut:3000});
        }
        else{
            if ($('#CHD').css("visibility")=="hidden" && ($('#CHD').find('.passengerBody').length)==1){
                $('#CHD').css("visibility", "visible");
            }
            else if ($('#CHD').css("visibility")=="hidden"){
                $('#CHD').css("visibility", "visible");
                $('#CHD').append($('#passengerBodyADT').clone().attr('id','passengerBodyCHD'));
            }
            CHDNumber++;
            numberOfPassengers++;
            $('#CHD .removeBTN').attr('id','removeCHD');
            $('#CHD .pastPassenger').attr('id','pastPassengerCHD' + pastCHD++ );
            $('#CHD .PassengerType').val('CHD');
            AddPassengerBody('CHD');

            var pastCHD=0;
            $('#CHD .pastPassenger').each(function(e) {
                $(this).attr('id', 'pastPassengerCHD' + pastCHD++);
            });

            var datepickerCHD=0;
            $('#CHD .datepicker').each(function(e) {
                $(this).attr('id', 'datepickerCHD' + datepickerCHD++);
            });



        }

    });

    $('.addINF').on('click', function () {
        if (numberOfPassengers == 9){
            toastr.clear();
            toastr.error(PassengerNumERR,'',{timeOut:3000});
        }
        else if (INFNumber>=ADTNumber){
            toastr.clear();
            toastr.error(PassengerINFERR,'',{timeOut:3000});

        }
        else{
            if ($('#INF').css("visibility")=="hidden" && ($('#INF').find('.passengerBody').length)==1){
                $('#INF').css("visibility", "visible");
            }
            else if ($('#INF').css("visibility")=="hidden"){
                $('#INF').css("visibility", "visible");
                $('#INF').append($('#passengerBodyADT').clone().attr('id','passengerBodyINF'));
            }
            INFNumber++;
            numberOfPassengers++;
            $('#INF .removeBTN').attr('id','removeINF');
            $('#INF .pastPassenger').attr('id','pastPassengerCHD' + pastINF++ );
            $('#INF .PassengerType').val('INF');
            AddPassengerBody('INF');

            var pastINF=0;
            $('#INF .pastPassenger').each(function(e) {
                $(this).attr('id', 'pastPassengerINF' + pastINF++);
            });

            var datepickerINF=0;
            $('#INF .datepicker').each(function(e) {
                $(this).attr('id', 'datepickerINF' + datepickerINF++);
            });


        }
    });

    $(document).on("click", '.pastPassenger', function(){
           $.ajax({
               method: 'get',
               url: '/admin/pastPassenger',
               contentType: false,
               processType: false
           }).done(function (data) {
               console.log(data['modal']);
               $('#passengerBodyADT0').append(data['modal']);
               $('#passengerBodyADT0 .modal-body').html(data['html']);
           });
    });
    // $('#' + passenger + ' .datepicker').each(function(e) {

    $(document).on("click", "tr.rows", function () {
        // console.log($(this).children('td').eq(1).text());
        $myRow=[];
        for( i=1;i<=5;i++ ){
            if ( i==1 ){
                if ($(this).children('td').eq(i).text() == 'نوزاد')
                    $myRow[0] = 'INF';
                else if ($(this).children('td').eq(i).text() == 'کودک')
                    $myRow[0] = 'CHD';
                else
                    $myRow[0] = 'ADT';
            }

            else if ( i==2 ){
                if ($(this).children('td').eq(i).text() == 'خانم')
                    $myRow[1] = 0;
                else
                    $myRow[1] = 1;
            }
            else if ( i==3 ){
                var $name=$(this).children('td').eq(i).text().split(' ');
                $myRow[2]=$name[0];
                $myRow[3]=$name[1];
            }
            else if (i==4 || i==5){
                $myRow[i]=toEnglishNum($(this).children('td').eq(i).text());
            }
        }

        // $('.modal').modal('toggle');
        // $(this).parents('.passengerBody').css("background-color", "red");
        console.log($(this).parents());



        console.log($myRow);

    });


    $(document).on("click", "#removeADT", function(){
        ADTNumber--;
        numberOfPassengers--;
        pastADT--;
        passengerBodyADT--;
        $(this).parents('.passengerBody').remove();
    });

    $(document).on("click", "#removeCHD", function(){
        if (($('#CHD').find('.passengerBody').length)-1 == 1){
            $('#CHD').css("visibility","hidden");
        }
        CHDNumber--;
        pastCHD--;
        numberOfPassengers--;
        passengerBodyCHD--;
        $(this).parents('.passengerBody').remove();
    });

    $(document).on("click", "#removeINF", function(){
        if (($('#INF').find('.passengerBody').length)-1==1)
            $('#INF').css("visibility","hidden");
        INFNumber--;
        pastINF--;
        numberOfPassengers--;
        passengerBodyINF--;
        $(this).parents('.passengerBody').remove();
    });

    $(document).on("click", "#editBtn", function() {
        $.ajax({
            method: 'get',
            url: '/admin/unReserve',
            contentType : false,
            processData: false

        });

        $('#registerPage').show();
        $('.btnSubmit').attr('disabled', false);
        // $('#h3passengerNumber').html("<?php echo  session('data')['passengerNumber'] ?>");
        // $('#spanPrice').text('<?php echo "ytt"; ?>');

        $('#reservePage').hide();

    });

    $(document).on("click", "#reserveBtn", function() {
        $.ajax({
            method: 'get',
            url: '/admin/reserved',
            contentType : false,
            processData: false

        }).done(function (data) {
            // console.log(data);

            if (data['status']=='Error'){
                swal({   title: "ارور!",   text: data['response'] ,type: "error" , confirmButtonText: 'اصلاح اطلاعات'}).
                then(function() {
                    $.ajax({
                        method: 'get',
                        url: '/admin/unReserve',
                        contentType : false,
                        processData: false

                    });

                    $('#registerPage').show();
                    $('.btnSubmit').attr('disabled', false);

                    $('#reservePage').hide();
                });
            }
            else {

                SweetAlert({   title: "با موفقیت انجام شد:)",   text: 'شماره مرجع: ' + data['response'],type: "success" , confirmButtonText: 'مشاهده بلیت ها'}).
                then(function() {
                    window.location.replace("/admin/ticket");

                });

            }

        });
    });


    // functions



    function getFieldValue(field) {
        var i=0;
        var output=[];
        $('input[data-bv-field^="passenger-'+field+'[]"]').each(function(e) {
            if ($(this).val()){
                output[i]=$(this).val();
                i++;
            }
        });
        return output;
    }

    function  toEnglishNum($number) {
        $number = $number.replace(/۱/g,"1");
        $number = $number.replace(/۲/g,"2");
        $number = $number.replace(/۳/g,"3");
        $number = $number.replace(/۴/g,"4");
        $number = $number.replace(/۵/g,"5");
        $number = $number.replace(/۶/g,"6");
        $number = $number.replace(/۷/g,"7");
        $number = $number.replace(/۸/g,"8");
        $number = $number.replace(/۹/g,"9");
        $number = $number.replace(/۰/g,"0");
        return $number;
    }

    //end function







});



