<!-- start: FIRST SECTION -->
<div class="container-fluid container-fullw">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body"> 
                    <?php
                    $flash_error = $this->session->flashdata('error');
                    $flash_success = $this->session->flashdata('success');
                    $postData = $this->session->flashdata('postData');
                    if (!empty($flash_success)) {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <?php echo $flash_success; ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    }

                    if (!empty($flash_error)) {
                        ?>
                        <div class="row">
                            <div class="col-md-12">  
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <?php echo $flash_error; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    if (!empty($playerDetails)) {
                        ?>

                        <div class="row margin-top-30">
                            <?php
                            if (empty($playerDetails['error'])) {
                                if (!empty($playerDetails['majorTeams'])) {
                                    $majorTeams = explode(",", $playerDetails['majorTeams']);
                                } else {
                                    $majorTeams = NULL;
                                }
                                if (!empty($playerDetails['imageURL']) && !empty($flag)) {
                                    $url = $playerDetails['imageURL'];
                                } else if (!empty($playerDetails['imageURL']) && empty($flag) && !file_exists($playerDetails['imageURL'])) {
                                    $url = $playerDetails['imageURL'];
                                } else {
                                    $url = base_url() . 'assets/images/default-user.png';
                                }
                                ?>
                            <?php 
     
                            if (empty($flag)) {            
                                $icon = "http://cdn.cricapi.com/country/Flag%20Of%20".$playerDetails['country'].".png";              
                            }else if (!empty($flag) && file_exists('./assets/user_files/player/' .$playerDetails['country']."/".$playerDetails['country'].".png")) { 
                                $icon = base_url() . "assets/user_files/player/".$playerDetails['country']."/".$playerDetails['country'].".png";             
                            }else{
                                 $icon = '';  
                            } 

                            ?>
                                <div class="col-sm-5 col-md-4">
                                    <div class="user-left">
                                        <div class="center">
                                            <h4><?php if(!empty($icon)) { ?><img src="<?php echo $icon; ?>" alt="India" title="India" height="48" style="margin-right:5px;"> <?php } ?> <?php echo!empty($playerDetails['fullName']) ? $playerDetails['fullName'] : '-'; ?></h4>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="user-image">
                                                    <div class="fileinput-new thumbnail"><img src="<?php echo $url; ?>"  alt="">
                                                    </div>                        
                                                </div>
                                            </div>

                                            <hr>

                                        </div>
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Player Information</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Country</td>
                                                    <td><?php echo!empty($playerDetails['country']) ? $playerDetails['country'] : '-'; ?></td>

                                                </tr>                    
                                                <tr>
                                                    <td>Born</td>
                                                    <td><?php echo!empty($playerDetails['born']) ? $playerDetails['born'] : '-'; ?></td>

                                                </tr>
                                                <tr>
                                                    <td>Batting Style</td>
                                                    <td><?php echo!empty($playerDetails['battingStyle']) ? $playerDetails['battingStyle'] : '-'; ?></td>

                                                </tr>
                                                <tr>
                                                    <td>Bowling Style</td>
                                                    <td><?php echo!empty($playerDetails['bowlingStyle']) ? $playerDetails['bowlingStyle'] : '-'; ?></td>

                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="col-sm-7 col-md-8">
                                   <?php if ($flag == 1) { ?>
                                        <div class="panel panel-white margin-bottom-60">
                                            <div class="panel-heading border-light">
                                                <h4 class="panel-title">Manage Player Information</h4>
                                            </div>
                                            <div class="panel-body">
                                                <form  action="<?php echo base_url(); ?>panel/players/updatePlayer/<?php echo $playerDetails['id']; ?>" id="updatePlayerForm" autocomplete="off" method="post" enctype="multipart/form-data" role="form">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label> Player Type <span class="symbol required"></span> </label>
                                                                <select class="cs-select cs-skin-elastic" name="playerType" id="playerType">
                                                                    <option value="" <?php if ($playerDetails['playerType'] == '') { ?>selected="selected"<?php } ?>>--Select Player Type--</option>
                                                                    <option value="Batsman"  <?php if ($playerDetails['playerType'] == 'Batsman') { ?>selected="selected"<?php } ?>>Batsman</option>
                                                                    <option value="Bowler"  <?php if ($playerDetails['playerType'] == 'Bowler') { ?>selected="selected"<?php } ?>>Bowler</option>
                                                                    <option value="All-rounder"  <?php if ($playerDetails['playerType'] == 'All-rounder') { ?>selected="selected"<?php } ?>>All-rounder</option>
                                                                    <option value="Wicket-Keeper"  <?php if ($playerDetails['playerType'] == 'Wicket-Keeper') { ?>selected="selected"<?php } ?>>Wicket-Keeper</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label> Credit <span class="symbol required"></span> </label>
                                                                <input type="text" placeholder="Enter Credits" name="credits" id="credits" class="form-control" value="<?php echo $playerDetails['credits']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary pull-right" type="submit">
                                                        Update <i class="fa fa-arrow-circle-right"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                 <?php } ?> 
                                    <div class="panel panel-white" id="activities">
                                        <div class="panel-heading border-light">
                                            <h4 class="panel-title text-primary">Player Profile</h4>
                                            <paneltool class="panel-tools" tool-collapse="tool-collapse" tool-refresh="load1" tool-dismiss="tool-dismiss"></paneltool>
                                        </div>
                                        <div collapse="activities" ng-init="activities = false" class="panel-wrapper">
                                            <div class="panel-body">
                                                <p><?php echo!empty($playerDetails['profile']) ? $playerDetails['profile'] : '-'; ?></p>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="panel panel-white margin-bottom-60">
                                        <div class="panel-heading border-light">
                                            <h4 class="panel-title">Major Teams Affiliated With</h4>
                                        </div>
                                        <div class="panel-body">
                                            <?php if (!empty($majorTeams)) { ?>
                                                <table class="table table-striped">
                                                   <tbody>

                                                <?php
                                                $count = 0;

                                                foreach ($majorTeams as $key => $value) {
                                                    if ($count == 0) {
                                                        echo '<tr>';
                                                    }
                                                    echo ' <td style="width: 30%;">' . $value . '</td>';
                                                    $count++;
                                                    if ($count == 3) {
                                                        echo '</tr>';
                                                        $count = 0;
                                                    }
                                                }

                                                if ($count != 0) {
                                                    for ($i = $count; $i < 3; $i++) {
                                                        echo '<td>&nbsp;</td>';
                                                    }

                                                    echo '</tr>';
                                                }
                                                ?>

                                                    </tbody>
                                                </table>
                                                    <?php } else { ?>
                                                <div class="col-md-12">
                                                    <div class="padding-30 text-center">
                                                        <h2 class="StepTitle"><i class="ti-face-sad fa-2x text-primary block margin-bottom-10"></i> No Major Team</h2>
                                                        <p>
                                                            <code>There is no major team</code>
                                                        </p>
                                                    </div>
                                                </div>
                                                    <?php } ?>

                                        </div>

                                    </div>
                                        


                                </div>
                             <?php } else { ?>
                                <div class="col-md-12">
                                    <div class="padding-30 text-center">
                                        <h2 class="StepTitle"><i class="ti-face-sad fa-2x text-primary block margin-bottom-10"></i>No Player Available</h2>
                                        <p>
                                            <code>Sorry! No data available, Please try again after some time.</code>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end: FIRST SECTION -->