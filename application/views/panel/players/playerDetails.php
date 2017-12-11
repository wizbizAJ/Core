<?php
if (!empty($playerDetails)) {
    ?>
    <form id="playerDetailForm" autocomplete="off" method="post" enctype="multipart/form-data" role="form">
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
                            <h4><?php if(!empty($icon)) { ?><img src="<?php echo $icon; ?>" alt="India" title="India" height="48" style="margin-right:5px;"><?php } ?><?php echo!empty($playerDetails['fullName']) ? $playerDetails['fullName'] : '-'; ?></h4>
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
                        <?php if (empty($flag)) { ?>
                                        <button class="btn btn-primary pull-right addPlayer" type="button">
                                            Add <i class="fa fa-arrow-circle-right"></i>
                                        </button>
                        <?php } ?>
                     <?php if (!empty($flag)) { ?>
                                        <div class="col-md-12">
                                            <div class="text-center">
                                                 <code>This Player is already added.</code>
                                            </div>
                                        </div>
                        <?php } ?>

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
    </form>
<script>
    // Add For Get Player Info        
         $('body').on('click','.addPlayer',function(){
               $("#playerDetailForm").find(":button").html('Submitting <i class="fa fa-spinner fa-spin"></i>');
               //$(".playerDetails").addClass('csspinner');
                var form = $(this);               
                $.ajax({
                    url: '<?php echo base_url(); ?>panel/players/addPlayer/<?php echo $pId; ?>',
                    type: 'POST',
                    data: form.serialize(), 
                    processData: false,
                    contentType: false,
                    //dataType: "json",
                    success: function (result) {                  
                     location.href = '<?php echo base_url(); ?>panel/players';
                    }
                });
            });
        // End For Get Player Info
</script>
<?php } ?>


