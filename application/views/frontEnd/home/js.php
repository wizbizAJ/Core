<script src="<?php echo base_url(); ?>assets/components/jquery.maskedinput/dist/jquery.maskedinput.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {

        $('.input-mask-date').mask('99/99/9999');

        var registerForm = $('#registerForm');
        var errorHandlerRegisterForm = $('.errorHandler', registerForm);
        var successHandlerRegisterForm = $('.successHandler', registerForm);

        $.validator.addMethod("regex", function (value, element, regexpr) {
            if (value)
            {
                return regexpr.test(value);
            } else
            {
                return true;
            }
        });

        $.validator.addMethod("anyDate",
                function (value, element) {
                    return value.match(/^(0?[1-9]|[12][0-9]|3[0-1])[/., -](0?[1-9]|1[0-2])[/., -](19|20)?\d{2}$/);
                },
                "Please enter the date in the format (DD/MM/YYYY)"
                );

        $.validator.addMethod("check_date_of_birth", function (value, element) {

            value = value.split("/").reverse().join("/");

            var birthDate = new Date(value);
            var day = birthDate.getDate();
            var month = birthDate.getMonth();
            var year = birthDate.getFullYear();

            var age = 18;

            var mydate = new Date();
            mydate.setFullYear(year, month - 1, day);

            var currdate = new Date();
            currdate.setFullYear(currdate.getFullYear() - age);

            return currdate > mydate;

        }, "You must be at least 18 years of age.");

        $('#registerForm').validate({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.attr("name") == "dd" || element.attr("name") == "mm" || element.attr("name") == "yyyy") {
                    error.insertAfter($(element).closest('.form-group').children('div'));
                } else {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
                }
            },
            ignore: "",
            rules: {
                registerEmail: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo base_url(); ?>register/checkEmail",
                        type: "post",
                    },
                },
                registerPassword: {
                    required: true,
                    regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@#!%*?&])[A-Za-z\d$@#!%*?&]{6,}/
                },
                registerDateOfBirth: {
                    required: true,
                    anyDate: true,
                    check_date_of_birth: true
                },
                registerAgree: {
                    required: true
                }
            },
            messages: {
                registerEmail: {
                    remote: 'This email Id is already registered.'
                },
                registerPassword: {
                    regex: 'The length of password should be minimum 6 characters. It should contain atleast 1 Uppercase Alphabet, 1 Lowercase Alphabet, 1 Number and 1 Special Character.'
                },
                registerAgree: {
                    required: 'In order to use our services, you must agree to CrickSkill\'s Terms of Service.'
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                successHandlerRegisterForm.hide();
                errorHandlerRegisterForm.show();
            },
            highlight: function (element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            onChange: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                successHandlerRegisterForm.show();
                errorHandlerRegisterForm.hide();
                $(form).find(":submit").html('Submitting <i class="fa fa-spinner fa-spin"></i>');
                //form.submit();                
                var $form = $(form);
                var formData = new FormData(form);
                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    //dataType: "json",
                    success: function (result) {
                        location.reload();
                    }
                });
            }
        });

    });

    function getAge(dateString)
    {
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
        {
            age--;
        }
        return age;
    }
</script>