$(document).ready(function() {

    $('.addADT').on('click', function () {
        AddPassengerBody('TemplateADT')

    });
    $('.addCHD').on('click', function () {
        if ($('#CHD').css("visibility", "hidden")){
            $('#CHD').css("visibility", "visible");
            $('#CHD').append($('#passengerBodyTemplateADT').clone().attr('id','passengerBodyTemplateCHD'));
        }
        AddPassengerBody('TemplateCHD')

    });
    $('.addINF').on('click', function () {
        if ($('#INF').css("visibility", "hidden")){
            $('#INF').css("visibility", "visible");
            $('#INF').append($('#passengerBodyTemplateADT').clone().attr('id','passengerBodyTemplateINF'));
        }

        AddPassengerBody('TemplateINF')
    });

    function AddPassengerBody(passenger) {
        var template     = "passengerBody",
        $templateEle = $('#' + template + passenger),

        $row = $templateEle.clone().removeAttr('id').insertBefore($templateEle).removeClass('hide');

        var $el = $row.find('select').eq(0).attr('name', template + '[]');
        $('#defaultForm').bootstrapValidator('addField', $el);


        for (i = 0; i <= 3; i++) {
            var $el = $row.find('input').eq(i).attr('name', template + '[]');
            $('#defaultForm').bootstrapValidator('addField', $el);
            // $el.attr('placeholder', 'Textbox #' + index);

        }


        $row.on('click', '.removeButton', function (e) {
            $('#defaultForm').bootstrapValidator('removeField', $el);
            $row.remove();
        });

    }

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
                        message: 'The first name is required and cannot be empty'
                    }
                }
            },
            'email': {
                validators: {
                    notEmpty: {
                        message: 'The first name is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            'tel': {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {
                        message: 'The tel is required and cannot be empty'
                    },
                    stringLength: {
                        min: 11,
                        max: 12,
                        message: 'The tel must be more 11 characters'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The username can only consist of alphabetical, number, dot and underscore'
                    },


                }
            },
            'gender[]': {
                validators: {
                    notEmpty: {
                        message: 'The gender is required'
                    }
                }
            },
            'passenger-fname[]': {
                validators: {
                    notEmpty: {
                        message: 'The passenger-fname is required and cannot be empty'
                    }
                }
            },
            'passenger-lname[]': {
                validators: {
                    notEmpty: {
                        message: 'The passenger-lname is required and cannot be empty'
                    }
                }
            },
            'passenger-id[]': {
                validators: {
                    notEmpty: {
                        message: 'The passenger-id is required and cannot be empty'
                    },
                    stringLength: {
                        min: 10,
                        max: 10,
                        message: 'The tel must be more 10 characters'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The passenger-id can only consist of number'
                    },


                }
            },
            'passenger-birthday[]': {
                validators: {
                    notEmpty: {
                        message: 'The passenger-birthday is required and cannot be empty'
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
});



