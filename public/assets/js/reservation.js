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
            console.log(error_formValid);

            // You can get the form instance and then access API
            var $form = $(e.target);
            // console.log($form.data('bootstrapValidator').getInvalidFields());

            // If you want to prevent the default handler (bootstrapValidator._onError(e))
            // e.preventDefault();
        })
        .on('success.form.bv', function (e) {
            // console.log('success.form.bv');
            error_formValid='false';
            console.log(error_formValid);

            // If you want to prevent the default handler (bootstrapValidator._onSuccess(e))
            // e.preventDefault();
        })
        .on('error.field.bv', function (e, data) {
            // console.log('error.field.bv -->', data);
        })
        .on('success.field.bv', function (e, data) {
            // console.log('success.field.bv -->', data);
        })
        .on('status.field.bv', function (e, data) {
            // I don't want to add has-success class to valid field container
            // data.element.parents('.form-group').removeClass('has-success');

            // I want to enable the submit button all the time
            data.bv.disableSubmitButtons(false);
        });


    // Validate the form manually
    $('#validateBtn').click(function () {
        $('#defaultForm').bootstrapValidator('validate');
    });

    var numberOfPassengers=ADTNumber+CHDNumber+INFNumber;
    $('#defaultForm').on('submit',function (e) {
        e.preventDefault();
        // $('.btnSubmit').attr('disabled', 'disabled');

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
                // var passenger_fname=[];var passenger_lname=[];var passenger_id=[];var passenger_birthday=[];

                $('.passenger-gender').each(function(e) {
                    if($(this).find(":selected").val()){
                        passenger_gender[i]=$(this).find(":selected").val();
                        i++;
                    }
                });
                // console.log(passenger_gender);
                // console.log(passenger_fname=getFieldValue('fname'));
                // console.log(passenger_lname=getFieldValue('lname'));
                // console.log(passenger_id=getFieldValue('id'));
                // console.log(passenger_birthday=getFieldValue('birthday'));



                $('#number').val( numberOfPassengers + '.' + ADTNumber + '.' + CHDNumber + '.' + INFNumber );
                var _token=$('input[name="_token"]').val();

                var formData=new  FormData();
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
                    },

                }).done(function (data) {
                    console.log(data);
                });

            }//end else

        }

    });
    // $('.btnSubmit').click(function (event) {
    //
    //
    //
    // });
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





    getBirthday('ADT');  //set birthday for first ADT passenger


    for(i=1;i<ADTNumber;i++)
        AddPassengerBody('ADT');

    if (CHDNumber>0){
        $('#CHD').css("visibility", "visible");
        $('#CHD').append($('#passengerBodyADT').clone().attr('id','passengerBodyCHD'));

        $('#CHD .removeBTN').attr('id','removeCHD');
        $('#CHD .PassengerType').val('CHD');
        for(i=0;i<CHDNumber;i++)
            AddPassengerBody('CHD');
    }

    if (INFNumber>0){
        $('#INF').css("visibility", "visible");
        $('#INF').append($('#passengerBodyADT').clone().attr('id','passengerBodyINF'));
        $('#INF .removeBTN').attr('id','removeINF');
        $('#INF .PassengerType').val('INF');
        for(i=0;i<INFNumber;i++)
            AddPassengerBody('INF');
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
            $('#CHD .PassengerType').val('CHD');
            AddPassengerBody('CHD');

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
            $('#INF .PassengerType').val('INF');
            AddPassengerBody('INF');

        }
    });

    $(document).on("click", "#removeADT", function(){
        ADTNumber--;
        numberOfPassengers--;
        $(this).parents('.passengerBody').remove();
    });

    $(document).on("click", "#removeCHD", function(){
        if (($('#CHD').find('.passengerBody').length)-1 == 1){
            $('#CHD').css("visibility","hidden");
        }
        CHDNumber--;
        numberOfPassengers--;
        $(this).parents('.passengerBody').remove();
    });

    $(document).on("click", "#removeINF", function(){
        if (($('#INF').find('.passengerBody').length)-1==1)
            $('#INF').css("visibility","hidden");
        INFNumber--;
        numberOfPassengers--;
        $(this).parents('.passengerBody').remove();
    });


    function AddPassengerBody(passenger) {

        var template     = "passengerBody",
            $templateEle = $('#' + template + passenger),

            $row = $templateEle.clone().removeAttr('id').insertBefore($templateEle).removeClass('hide');

        $('#' + passenger + '.removeBTN').attr('id','remove' + passenger);

        var $el = $row.find('select').eq(0).attr('name', template + '[]');
        $('#defaultForm').bootstrapValidator('addField', $el);

        getBirthday(passenger);

        for (j = 0; j <= 4; j++) {
            var $el = $row.find('input').eq(j).attr('name', template + '[]');
            $('#defaultForm').bootstrapValidator('addField', $el);

        }
    }


    function getBirthday(passenger) {
        $.ajax({
            method: 'get',
            url: '/admin/getBirthday/'+passenger,
            contentType : false,
            processData: false
        }).done(function (data) {
            console.log(data);
            start=data['start'];
            end=data['end'];
            $('#' + passenger + ' .datepicker').each(function(e) {
                $(this).persianDatepicker({

                    cellWidth: 50,
                    cellHeight: 30,
                    fontSize: 18,


                    startDate: start,
                    endDate: 'today'

                });
            });
        });


    }












});



