<!-- start: NAVBAR HEADER -->
<div class="navbar-header">
    <button href="javascript:void(0)" class="menu-mobile-toggler btn no-radius pull-left hidden-md hidden-lg" id="horizontal-menu-toggler" data-toggle="collapse" data-target=".horizontal-menu">
        <i class="fa fa-bars"></i>
    </button>
    <a class="navbar-brand" href="<?php echo base_url(); ?>panel/welcome"> 
        <?php
        if (!empty($logo)) {
            $url = base_url() . 'assets/user_files/logo/' . $logo;
        }
        ?>
        <img alt="<?php $logoTitle ?>" src="<?php echo base_url() . 'assets/user_files/timthumb.php?src=' . $url . '&h=50'; ?>">
    </a>
    <a class="navbar-brand navbar-brand-collapsed" href="<?php echo base_url(); ?>panel/welcome"> 
        <img alt="<?php $logoTitle ?>" src="<?php echo base_url() . 'assets/user_files/timthumb.php?src=' . $url . '&h=25&w=89'; ?>">
    </a>
</div>
<!-- end: NAVBAR HEADER -->
<!-- start: NAVBAR COLLAPSE -->
<div class="navbar-collapse collapse">
    <ul class="nav navbar-left hidden-sm hidden-xs">
        <li class="sidebar-toggler-wrapper">
            <div>
                <button href="javascript:void(0)" class="btn sidebar-toggler visible-md visible-lg">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </li>
        <li>
            <a href="#" class="toggle-fullscreen"> <i class="fa fa-expand expand-off"></i><i class="fa fa-compress expand-on"></i></a>
        </li>
        <li>
            <form role="search" class="navbar-form main-search">
                <div class="form-group">
                    <input type="text" placeholder="Enter search text here..." class="form-control">
                    <button class="btn search-button" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </li>
    </ul>
    <!-- start: MENU TOGGLER FOR MOBILE DEVICES -->
    <div class="close-handle visible-xs-block menu-toggler" data-toggle="collapse" href=".navbar-collapse">
        <div class="arrow-left"></div>
        <div class="arrow-right"></div>
    </div>
    <!-- end: MENU TOGGLER FOR MOBILE DEVICES -->
</div>
<!-- end: NAVBAR COLLAPSE -->