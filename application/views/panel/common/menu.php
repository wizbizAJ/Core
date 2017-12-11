<div class="horizontal-nav-container">
    <ul class="nav navbar-nav no-border">
        <li class="<?php
        if ($mainMenu == 'welcome') {
            echo 'active';
        }
        ?>">
            <a href="<?php echo base_url(); ?>panel/welcome"> <div class="lettericon" data-text="Dashboard" data-size="sm" data-char-count="2" data-color="auto"></div> <span> Dashboard</span> </a>
        </li>
        <?php if (in_array(11, $permission)) { ?>
            <li class="<?php
            if ($mainMenu == 'players') {
                echo 'active';
            }
            ?>">
                <a href="<?php echo base_url(); ?>panel/players"> <div class="lettericon" data-text="Players" data-size="sm" data-char-count="2" data-color="auto"></div> <span> Players</span> </a>
            </li>
        <?php } ?>

        <?php if (in_array(10, $permission)) { ?>
            <li class="<?php
            if ($mainMenu == 'matches') {
                echo 'active';
            }
            ?>">
                <a href="<?php echo base_url(); ?>panel/matches"> <div class="lettericon" data-text="Matches" data-size="sm" data-char-count="2" data-color="auto"></div> <span> Matches</span> </a>
            </li>
        <?php } ?>

        <?php if (in_array(9, $permission)) { ?>
            <li class="<?php
            if ($mainMenu == 'customer') {
                echo 'active';
            }
            ?>">
                <a href="<?php echo base_url(); ?>panel/customer"> <div class="lettericon" data-text="Customers" data-size="sm" data-char-count="2" data-color="auto"></div> <span> Customers</span> </a>
            </li>
        <?php } ?>


        <?php if (in_array(7, $permission) || in_array(8, $permission)) { ?>
            <li class="dropdown <?php
            if ($mainMenu == 'league' || $mainMenu == 'cms') {
                echo 'active';
            }
            ?>">
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"> <div class="lettericon" data-text="Management" data-size="sm" data-char-count="2" data-color="auto"></div> <span> Management </span> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php if (in_array(7, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'league') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/league"> <span> League </span> </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array(8, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'cms') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/cms"> <span> Cms </span></a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>


        <?php if (in_array(5, $permission) || in_array(6, $permission)) { ?>
            <li class="dropdown  <?php
            if ($mainMenu == 'role' || $mainMenu == 'user') {
                echo 'active';
            }
            ?>">
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"> <div class="lettericon" data-text="User Management" data-size="sm" data-char-count="2" data-color="auto"></div> <span> User Management</span> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php if (in_array(5, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'role') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/role"> <span> Role</span> </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array(6, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'user') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/user"> <span> User</span> </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>



        <?php if (in_array(1, $permission) || in_array(2, $permission) || in_array(3, $permission) || in_array(4, $permission)) { ?>

            <li class="dropdown <?php
            if ($mainMenu == 'point' || $mainMenu == 'selection_criteria' || $mainMenu == 'site' || $mainMenu == 'email') {
                echo 'active';
            }
            ?>">
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"> <div class="lettericon" data-text="Settings" data-size="sm" data-char-count="2" data-color="auto"></div> <span> Settings</span> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php if (in_array(4, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'point') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/settings/point"> <span> Point Management </span> </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array(3, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'selection_criteria') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/settings/selection_criteria"> <span> Selection Criteria </span> </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array(1, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'site') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/settings/site"> <span> Site Setting </span> </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array(2, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'email') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/settings/email"> <span> Email Setting </span> </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <?php if (in_array(12, $permission) || in_array(13, $permission) || in_array(14, $permission)) { ?>
            <li class="dropdown  <?php
            if ($mainMenu == 'withdraw' || $mainMenu == 'win' || $mainMenu == 'bankDetails') {
                echo 'active';
            }
            ?>">
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"> <div class="lettericon" data-text="More" data-size="sm" data-char-count="2" data-color="auto"></div> <span>More</span> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php if (in_array(12, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'withdraw') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/withdraw"> <span> Withdraw</span> </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array(13, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'win') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/win"> <span> Win </span> </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array(14, $permission)) { ?>
                        <li class="<?php
                        if ($mainMenu == 'bankDetails') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo base_url(); ?>panel/bankDetails"> <span> Bank Details</span> </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown <?php
        if ($mainMenu == 'profile') {
            echo 'active';
        }
        ?>">
            <a href="#" class="dropdown-toggle nav-user-wrapper" data-toggle="dropdown"> 
                <?php
                if (!empty($panel->profileImage)) {
                    $url = base_url() . 'assets/user_files/profile/' . $panel->profileImage;
                } else {
                    $url = base_url() . 'assets/images/default-user.png';
                }
                ?>
                <img alt="" src="<?php echo base_url() . 'assets/user_files/timthumb.php?src=' . $url . '&h=50&w=50'; ?>">
                <span><?php echo $panel->userName; ?></span> <span class="caret"></span> 
            </a>

            <ul class="dropdown-menu pull-right animated fadeInRight">
                <li class="<?php
                if ($mainMenu == 'profile' && $subMenu == 'my') {
                    echo 'active';
                }
                ?>">
                    <a href="<?php echo base_url(); ?>panel/profile/my"> My Profile </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo base_url(); ?>panel/logout"> Log Out </a>
                </li>
            </ul>
        </li>
    </ul>
</div>
<!-- start: MENU TOGGLER FOR MOBILE DEVICES -->
<div class="close-handle visible-xs-block visible-sm-block menu-toggler" data-toggle="collapse" data-target=".horizontal-menu">
    <div class="arrow-left"></div>
    <div class="arrow-right"></div>
</div>
<!-- end: MENU TOGGLER FOR MOBILE DEVICES -->