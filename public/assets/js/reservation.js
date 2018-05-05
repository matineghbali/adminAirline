$(document).ready(function() {
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
                        regexp:  /^[0-9]+$/,
                        message: 'شماره تلفن را با ارقام انگلیسی وارد کنید'
                    },


                }
            },
            'gender[]': {
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

                    stringLength: {
                        min: 10,
                        max: 10,
                        message: 'کد ملی باید 10 رقم باشد'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'کد ملی را ارقام انگلیسی وارد کنید'
                    },
                }
            },
            'passenger-birthday[]': {
                validators: {
                    notEmpty: {
                        message: 'تاریخ تولد خود را انتخاب کنید'
                    }
                }
            },
        }
    })
        .on('error.form.bv', function (e) {
            console.log('error.form.bv');

            // You can get the form instance and then access API
            var $form = $(e.target);
            console.log($form.data('bootstrapValidator').getInvalidFields());

            // If you want to prevent the default handler (bootstrapValidator._onError(e))
            // e.preventDefault();
        })
        .on('success.form.bv', function (e) {
            console.log('success.form.bv');

            // If you want to prevent the default handler (bootstrapValidator._onSuccess(e))
            // e.preventDefault();
        })
        .on('error.field.bv', function (e, data) {
            console.log('error.field.bv -->', data);
        })
        .on('success.field.bv', function (e, data) {
            console.log('success.field.bv -->', data);
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

    $('#submit').click(function () {
        $('#number').val( numberOfPassengers + '.' + ADTNumber + '.' + CHDNumber + '.' + INFNumber );
    });


    for(i=1;i<ADTNumber;i++)
        AddPassengerBody('ADT');

    if (CHDNumber>0){
        $('#CHD').css("visibility", "visible");
        $('#CHD').append($('#passengerBodyADT').clone().attr('id','passengerBodyCHD'));

        $('#CHD .removeBTN').attr('id','removeCHD');
        for(i=0;i<CHDNumber;i++)
            AddPassengerBody('CHD');
    }

    if (INFNumber>0){
        $('#INF').css("visibility", "visible");
        $('#INF').append($('#passengerBodyADT').clone().attr('id','passengerBodyINF'));
        $('#INF .removeBTN').attr('id','removeINF');
        for(i=0;i<INFNumber;i++)
            AddPassengerBody('INF');
    }

    var PassengerNumERR='تعداد مسافرها نمی تواند بیشتر از 9 باشد، درصورت نیاز تعداد بیشتر جداگانه صادر کنید';
    var PassengerINFERR='تعداد نوزاد نمی تواند بیشتر از بزرگسال باشد';

    $('.addADT').on('click', function () {
        if (numberOfPassengers == 9){
            alert(PassengerNumERR)
        }
        else{
            ADTNumber++;
            numberOfPassengers++;
            AddPassengerBody('ADT');
        }


    });

    $('.addCHD').on('click', function () {
        if (numberOfPassengers == 9){
            alert(PassengerNumERR)
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
            AddPassengerBody('CHD');

        }

    });

    $('.addINF').on('click', function () {
        if (numberOfPassengers == 9){
            alert(PassengerNumERR);
        }
        else if (INFNumber>=ADTNumber)
            alert(PassengerINFERR);
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


        for (j = 0; j <= 3; j++) {
            var $el = $row.find('input').eq(j).attr('name', template + '[]');
            $('#defaultForm').bootstrapValidator('addField', $el);

        }
    }





});



