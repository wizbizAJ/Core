<header>
    <div class="container">       
        <!--***** Logo ********-->
        <div class="logo col-sm-3 col-md-2">
            <a href="<?php echo base_url(); ?>">
                <?php
                if (!empty($logo)) {
                    $url = base_url() . 'assets/user_files/logo/' . $logo;
                } else {
                    $url = base_url() . 'assets/frontEnd/images/logo.png';
                }
                ?>
                <img src="<?php echo $url; ?>" alt="<?php $logoTitle ?>" class="img-responsive" style="width: 126px;">
            </a>
        </div>
        <!--***** Main Menu ********-->
        <div class="home-login <?php if (empty($customer)) { ?>notloginDiv<?php } ?> col-sm-9 col-md-10">
            <?php if (empty($customer)) { ?>
                <div class="login-box hide">
                    <form class="form-inline text-right" action="<?php echo base_url(); ?>login" method="post" name="loginForm" id="loginForm" >
                        <div class="col-sm-10 paddingr-none login-left col-xs-9">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></div>
                                    <input type="email" class="form-control" id="loginEmail" name="loginEmail" placeholder="Enter Email ID">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group loginPasswordEye">
                                    <div class="input-group-addon ex-padd"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                    <input type="password" class="form-control" id="loginPassword" name="loginPassword" placeholder="Enter Password">
                                    <span class="passwordEye"></span>
                                </div>
                            </div>
                        </div>                    
                        <!--<div class="col-sm-2 login-right col-xs-3">-->
                        <div class="login-right">
                            <button type="submit" class="btn btn-default" data-toggle="modal" data-target=".login-modal" data-id="1098211" tabindex="0" >
                                <i class="fa fa-paper-plane" aria-hidden="true"></i><br>Login
                            </button>
                        </div>
                        <!--</form>-->
                        <div class="clearfix"></div>
                        <div class="forgot text-right">
                            <a href="javascript:void(0)" class="createAccountLink" >Create a new account ?</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="javascript:void(0)" data-toggle="modal" data-target=".forgot-password-modal"  >Forgot Password ?</a>
                        </div>
                </div>
            <?php } ?>

            <div class="team-slider">

            </div>
        </div>

        <div class="clearfix"></div>
    </div>
</header>
<div class="clear"></div>

<?php if (!empty($customer)) { ?>
    <div class="user-panel">
        <div class="container">
            <div class="col-sm-7">
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Hi<?php
                        if (!empty($customer->name)) {
                            echo ' ' . $customer->name;
                        } else if (!empty($customer->teamName)) {
                            echo ' ' . $customer->teamName;
                        }
                        ?>, <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                    </button>                   
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo base_url(); ?>profile">Edit Profile</a>
                        <a class="dropdown-item" href="<?php echo base_url(); ?>leagues/my">My Leagues</a>
                        <a class="dropdown-item" href="<?php echo base_url(); ?>wallet">Wallet</a>
                        <a class="dropdown-item" href="<?php echo base_url(); ?>logout">Logout</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-5 wallet text-right">
                <?php if (!empty($match_id) && isset($match_id)) {
                    ?>
                    <div class="makeYourTeamLink" >
                        <a href="<?php echo base_url() . 'myteam/' . $match_id; ?>">Make Your Team</a>

                    </div>
                <?php } ?>
                <div class="walletLink" >
                    <img src="<?php echo base_url(); ?>assets/frontEnd/images/wallet.png" alt="Wallet">
                    <a href="javascript:void(0)">Rs : <?php echo number_format($walletBalance[0]->balance, 2); ?>/-</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
<?php } ?>


<!-- Forgot Password Modal -->
<div class="modal fade forgot-password-modal"  id="forgotPasswordModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Forgot Password</h4>
            </div>
            <div class="modal-body">             

                <div class="forgotStatus"></div>
                <div class="form-group errorForgot hide">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>                            
                        <div id="error"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <form action="<?php echo base_url(); ?>forgot_password" method="post" id="forgotPasswordForm" >

                            <div class="col-md-12">
                                <div class="form-group">

                                    <input type="email" placeholder="Enter your email address" class="form-control" id="email" name="email" value="<?php echo @$postData['email']; ?>">
                                </div>
                            </div>

                            <div class="action_btns text-right">

                                <button type="submit" class="btn btn-default" name="submitForgotPassword">Submit</button>

                            </div>
                        </form>
                    </div> 
                </div>

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->

<!-- Login Modal -->
<div class="modal fade login-modal"  id="loginModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
            </div>
            <div class="modal-body">             

                <div class="forgotStatus"></div>
                <div class="form-group errorForgot hide">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>                            
                        <div id="error"></div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="home-login popupLogin">
                            <form class="form-inline text-right" action="<?php echo base_url(); ?>login" method="post" name="loginForm2" id="loginForm2" >
                                <div class="col-sm-10 paddingr-none login-left col-xs-9">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></div>
                                            <input type="email" class="form-control" id="loginEmail" name="loginEmail" placeholder="Enter Email ID">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group loginPasswordEye">
                                            <div class="input-group-addon ex-padd"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                            <input type="password" class="form-control" id="loginPassword" name="loginPassword" placeholder="Enter Password">
                                            <span class="passwordEye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 login-right col-xs-3">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i><br>Login
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="forgot text-left">
                                        <a href="javascript:void(0)" class="createAccountLink" >Create a new account ?</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="javascript:void(0)" data-toggle="modal" data-target=".forgot-password-modal"  >Forgot Password ?</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div> 
                </div>

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function () {

        var loginForm = $('#loginForm2');
        var errorHandlerLoginForm = $('.errorHandler', loginForm);
        var successHandlerLoginForm = $('.successHandler', loginForm);

        $('#loginForm2').validate({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.attr("name") == "dd" || element.attr("name") == "mm" || element.attr("name") == "yyyy") {
                    error.insertAfter($(element).closest('.form-group').children('div'));
                }
            },
            ignore: "",
            rules: {
                loginEmail: {
                    required: true,
                    email: true
                },
                loginPassword: {
                    required: true
                }
            },
            messages: {
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                successHandlerLoginForm.hide();
                errorHandlerLoginForm.show();
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
                successHandlerLoginForm.show();
                errorHandlerLoginForm.hide();
                $(form).find(":submit").html('<i class="fa fa-spinner fa-spin"></i><br>Login');
                //form.submit();                
                var $form = $(form);
                var formData = new FormData(form);
                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (result) {
                        if (result['status'] === 0)
                        {
                            $(form).find(":submit").html('<i class="fa fa-paper-plane" aria-hidden="true"></i><br>Login');
                            swal("error", result['message'] + " :(", "error");
                        } else if (result['status'] === 1)
                        {
                            $.ajax({
                                url: $form.attr('action'),
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                //dataType: "json",
                                success: function (result) {
                                    $.ajax({
                                        url: '<?php echo base_url(); ?>welcome/headerFirstMatch',
                                        type: 'POST',
                                        data: '',
                                        //dataType: "json",
                                        success: function (result) {
                                            if (result == "Not Found")
                                            {
                                                location.reload();
                                            } else {
                                                window.location.href = "<?php echo base_url(); ?>leagues/" + result;
                                            }
                                        }
                                    });

                                }
                            });
                        }
                    }
                });
            }
        });


        /*==== Start ::: Logout ====*/
        var logoutForm = $('#logoutForm2');
        var errorHandlerLogoutForm = $('.errorHandler', logoutForm);
        var successHandlerLogoutForm = $('.successHandler', logoutForm);

        $('#logoutForm2').validate({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.attr("name") == "dd" || element.attr("name") == "mm" || element.attr("name") == "yyyy") {
                    error.insertAfter($(element).closest('.form-group').children('div'));
                }
            },
            ignore: "",
            rules: {
            },
            messages: {
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                successHandlerLogoutForm.hide();
                errorHandlerLogoutForm.show();
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
                successHandlerLogoutForm.show();
                errorHandlerLogoutForm.hide();
                $(form).find(":submit").html('<i class="fa fa-spinner fa-spin"></i><br>Logout');
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
        /*==== End ::: Logout ====*/
    });
</script>
<!-- Login Modal -->