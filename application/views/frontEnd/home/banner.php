<div class="home-banner">
    <div class="player-img">
        <img src="<?php echo base_url(); ?>assets/frontEnd/images/crick-btm.png" alt="Img">
    </div>
    <div class="container">
        <div class="col-sm-12 col-md-5">
            <?php if (empty($customer)) { ?>
                <div class="banner-left">
                    <div class="left-title col-sm-10">
                        <h2>Sign up :</h2>
                    </div>
                    <div class="col-sm-2 paddingl-none paddingr-none" >
                        <ul class="left-social ">
                            <li class=""><a href="<?php echo $facebookLoginUrl; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li class=""><a href="<?php echo $googleLoginUrl; ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div class="signup-form">
                        <form action="<?php echo base_url(); ?>register" method="post" name="registerForm" id="registerForm" >
                            <?php
                            $flash_error = $this->session->flashdata('registerError');
                            $flash_success = $this->session->flashdata('registerSuccess');
                            if (!empty($flash_success)) {
                                ?>
                                <div class="form-group">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <?php echo $flash_success; ?>
                                    </div>
                                </div>
                            <?php
                            }
                            if (!empty($flash_error)) {
                                ?>
                                <div class="form-group">
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <?php echo $flash_error; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <input type="email" class="form-control" id="registerEmail" name="registerEmail" placeholder="Email">
                            </div>
                            <div class="form-group signupPasswordEye">
                                <input type="password" class="form-control" id="registerPassword" name="registerPassword" placeholder="Password">
                                <span class="passwordEye"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" id="registerDateOfBirth" name="registerDateOfBirth" class="form-control input-mask-date" placeholder="Date of Birth (DD/MM/YYYY)">
                            </div>
                            <lable class="help-block" style="margin-top: -12px;padding: 0 15px;">User should be over 18 years.</lable>
                            <div class="form-group">
                                <div class="checkbox">
                                    <input type="checkbox" name="registerAgree" id="registerAgree" value="1">
                                    <label for="registerAgree"> I agree to Crickskill's <a href="#">T&C</a> </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-default">Start Playing</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-sm-12 col-md-7 ">
             <?php if (empty($customer)) { ?>
            <div class="homeLogin">
                <div class="left-title">
                        <h2>Login :</h2>
                    </div>
                <div class="home-login">
                    <div>
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
                                <button type="submit" class="btn btn-default"  >
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
                </div>
            </div>      
             <?php } ?>
            <div class="banner-right">
                <h2>What is Cricskill.com Foundation?</h2>
                <p>Cricskill.com Foundation is the philanthropic arm of Cricskill.com that aims to positively impact and provide sustainable growth impetus to deserving individuals, societies, bodies or organization.</p>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>