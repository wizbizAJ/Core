<section class="container inner-mid player-live-point"> 
    <!-- Tab panes -->
    <div class="my-leagues col-sm-8 col-md-8  paddingl-none">
        <div class="tab-menu col-sm-8 col-md-8 paddingl-none"> 
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class=" text-center paddingl-none paddingr-none">
                    <a href="#myleagues" aria-controls="My Leagues" role="tab" data-toggle="tab">
                        My Leagues
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
        <div class="my-leagues">
        <?php if (!empty($myLeagues)) { ?>
            <div class="tab-content">
                <?php 
                foreach ($myLeagues as $mlKey => $mlVal) {
                    
                    $currentTime = $this->setting_model->converTimeZone('d/m/Y H:i:s',date('Y-m-d H:i:s'));
                    $matchTime = $this->setting_model->converTimeZone('d/m/Y H:i:s',$mlVal['joinLeague']->startDateTime);
                    $rankArray = array();
                                
                    if(strtotime($matchTime) < strtotime($currentTime)){
                        $rankArray = $this->point_model->getRank1($mlVal['joinLeague']->matchId,$mlVal['joinLeague']->leagueId);
                    } 
                    
                    $teamInfo1 = $this->customer_model->getTeamInfoByMatchId($mlVal['joinLeague']->customerId, $mlVal['joinLeague']->matchId);
                ?>
                <div role="tabpanel" class="tab-pane active" id="myleagues">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <div class="col-sm-2 col-xs-6 text-center paddingl-none paddingr-none">
                                    <h3><?php echo $mlVal['joinLeague']->title; ?></h3>
                                </div>
                                <div class="col-sm-2 col-xs-6 text-center paddingr-none">
                                    <h5>Team : <?php echo count($mlVal['otherCustomer'])+1; ?>/<?php echo $mlVal['joinLeague']->maxTeam; ?></h5>
                                </div>
                                <div class="col-sm-6 col-xs-6 text-left paddingr-none paddingl-none">
                                    <h5>
                                        <?php echo $mlVal['joinLeague']->matchName; ?>
                                        <span>(<?php echo $this->setting_model->converTimeZone('d/m/Y H:i', $mlVal['joinLeague']->startDateTime); ?>)</span>
                                    </h5>
                                </div>
                                <div class="col-sm-1 col-xs-4 text-left ">
                                    <?php if(!empty($rankArray[$mlVal['joinLeague']->customerId][$mlVal['joinLeague']->customerTeamId]['score'])){ ?>
                                    <a href="<?php echo base_url(); ?>scorecard/<?php echo $mlVal['joinLeague']->id; ?>" target="_blank" > <img src="<?php echo base_url(); ?>assets/frontEnd/images/score-white.png" alt="Score"></a>
                                    <?php } ?>
                                </div>
                                <div class="col-sm-1 col-xs-4 text-right">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-<?php echo $mlKey; ?>" aria-expanded="true" aria-controls="collapseOne" class="collapsed getTeamStatistic" id="<?php echo $mlVal['joinLeague']->customerTeamId.'#'.$mlVal['joinLeague']->leagueId.'#'.$mlVal['joinLeague']->id; ?>"></a>
                                    </h4>
                                </div>
                                <div class="clearfix"></div>  
                            </div>
                            <div id="collapseOne-<?php echo $mlKey; ?>" class="panel-collapse collapse <?php if($mlKey==0){ echo 'in'; } ?>" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="point-league-team">
                                        <div>
                                            <ul class="getTeamStatistic" id="<?php echo $mlVal['joinLeague']->customerTeamId.'#'.$mlVal['joinLeague']->leagueId.'#'.$mlVal['joinLeague']->id; ?>">
                                                <li class="col-sm-1">
                                                    <?php if(!empty($rankArray)){
                                                        echo $rankArray[$mlVal['joinLeague']->customerId][$mlVal['joinLeague']->customerTeamId]['rank'];
                                                    } ?>
                                                </li>
                                                <li class="col-sm-4"><?php echo $mlVal['joinLeague']->teamName; ?></li>
                                                <li class="col-sm-4">
                                                    <?php
                                                    foreach ($teamInfo1 as $key1 => $value1)
                                                    {
                                                        if ($value1->id == $mlVal['joinLeague']->customerTeamId) {

                                                            echo 'Team #' . intVal($key1 + 1);
                                                        }
                                                    }
                                                    ?>
                                                </li>
                                                <li class="col-sm-3">
                                                    <?php if(!empty($rankArray)){ ?>
                                                    Point : <?php echo $rankArray[$mlVal['joinLeague']->customerId][$mlVal['joinLeague']->customerTeamId]['score']; ?>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                        <?php if(!empty($mlVal['otherCustomer'])){ ?>
                                        <div class="border-btm"></div>
                                        <?php } ?>
                                        <?php 
                                        foreach ($mlVal['otherCustomer'] as $ocKey => $ocVal)
                                        { 
                                            $teamInfo1 = $this->customer_model->getTeamInfoByMatchId($ocVal->customerId, $ocVal->matchId);
                                        ?>
                                        <div class="">
                                            <ul class="getTeamStatistic" id="<?php echo $ocVal->customerTeamId.'#'.$ocVal->leagueId.'#'.$ocVal->id; ?>">
                                                <li class="col-sm-1">
                                                    <?php if(!empty($rankArray)){
                                                        echo $rankArray[$ocVal->customerId][$ocVal->customerTeamId]['rank'];
                                                    } ?>
                                                </li>
                                                <li class="col-sm-4"><?php echo $ocVal->teamName; ?></li>
                                                <li class="col-sm-4">
                                                    <?php
                                                    foreach ($teamInfo1 as $key1 => $value1)
                                                    {
                                                        if ($value1->id == $ocVal->customerTeamId) {

                                                            echo 'Team #' . intVal($key1 + 1);
                                                        }
                                                    }
                                                    ?>
                                                </li>
                                                <li class="col-sm-3">
                                                    <?php if(!empty($rankArray)){ ?>
                                                    Point : <?php echo $rankArray[$ocVal->customerId][$ocVal->customerTeamId]['score']; ?>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                        <?php if(count($mlVal['otherCustomer']) != ($ocKey+1)){ ?>
                                        <div class="border-btm"></div>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>		 		
                </div>  
            <?php } ?>
            </div> 
        <?php }else{ ?>
            You haven't joined any leagues.
        <?php } ?>
        </div>
    </div>   
    <?php if (!empty($myLeagues)) { ?>
    <div class="player-total-point col-sm-4 col-md-4 paddingl-none paddingr-none">
        <div class="teamInfoStatistic"></div>
    </div>   
    <?php } ?>
    <div class="clearfix"></div>   
</section>