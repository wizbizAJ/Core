<div class="row">
    <div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        <div class="logo text-center">
            <?php
            if(!empty($logo))
            {
                $url = base_url().'assets/user_files/logo/'.$logo;
            ?>
            <img src="<?php echo $url; ?>" alt="<?php $logoTitle ?>" class="img-responsive" style="width: 126px;">
            <?php }else{ ?>&nbsp;<?php } ?>
        </div>
        
        <p class="text-center text-dark text-bold text-extra-large margin-top-15">
            Welcome to <?php echo $siteTitle; ?>
        </p>
        <p class="text-center">
            Please enter your username and password to log in.
        </p>
        <!-- start: LOGIN BOX -->
        <div class="box-login">
            <?php
            $flash_error = $this->session->flashdata('error');
            $flash_success = $this->session->flashdata('success');

            if(!empty($flash_success))
            {
            ?>
              <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <?php echo $flash_success; ?>
              </div>
            <?php } 

            if(!empty($flash_error))
            {
            ?>
              <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <?php echo $flash_error; ?>
              </div>
            <?php } ?>
            <form class="form-login" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" autofocus="autofocus" autocomplete="off" >
                </div>
                <div class="form-group form-actions">

                    <input type="password" class="form-control password" name="password" placeholder="Password" autocomplete="off" >

                </div>
                <div class="text-center">
                    <a href="<?php echo base_url(); ?>panel/forgot_password"> I have forgotten my password </a>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-red btn-block">
                        Login
                    </button>
                </div>
            </form>
            <!-- start: COPYRIGHT -->
            <div class="copyright">
                <?php echo $reservedRight; ?>
            </div>
            <!-- end: COPYRIGHT -->
        </div>
        <!-- end: LOGIN BOX -->
    </div>
</div>