<?php foreach($leagues as $leagueKey => $leagueVal){  ?>
<div class="col-sm-6 col-md-3 cupon-block">
    <div class="shadow">
        <div class="win-price">
            <h5><?php echo $leagueVal['league']->title; ?></h5>
        </div>
        <div class="pack-detail">
            <p class="pay">Pay Rs : <?php echo number_format($leagueVal['league']->joinFees); ?>/-</p>
            <p class="team"><?php echo count($leagueVal['teamsJoin']); ?> /<?php echo $leagueVal['league']->maxTeam; ?>  Teams</p>
            <div class="winner">
                <a href="#"><?php echo $leagueVal['league']->totalWinners; ?> Winners</a>
                <div class="pop-winner">
                    <div class="rank-title col-sm-6 text-center">Rank</div>
                    <div class="price-title col-sm-6 text-center">Price</div>
                    <?php 
                    $rankValue='';
                    foreach($leagueVal['leagueWinner'] as $winnerKey => $winnerVal){
                     
                        if(strpos($winnerVal->rank, '-')) {
                        $rank = explode("-",$winnerVal->rank);
                        if($rank[0]==$rank[1])
                        {
                            $rankValue = $rank[0];
                            
                        }else{
                             $rankValue = $winnerVal->rank;
                        }
                        }else{
                             $rankValue = $winnerVal->rank;
                        }
                        ?>
                    <div class="rank-detail col-sm-6 text-center"><?php echo $rankValue; ?></div>
                    <div class="price-detail col-sm-6 text-center">Rs : <?php echo number_format($winnerVal->prize); ?>/-</div>
                    <?php } ?>
                </div>
            </div>
            <div class="join-league text-right">
                <?php
                $customerLeague = $this->league_model->getLeagueTeamByCustomer($matchId,$leagueVal['league']->id,$customer->id);
                $teamInfo = $this->team_model->getUnselectedCustomerTeam($customer->id,$matchId,$leagueVal['league']->id);
                
                if(empty($customerLeague) || (!empty($teamInfo) && $leagueVal['league']->isMultiEntry)){
                ?>
                <a href="javascript:void(0)" id="<?php echo $matchId.'#'.$leagueVal['league']->id; ?>" class="joinNow" >Join Now</a>
                <?php }else{ ?>
                <a href="javascript:void(0)">Joined</a>
                <?php } ?>
            </div>
            <div class="league-option">
                <?php if($leagueVal['league']->isMultiEntry){ ?>
                <div class="multi-league">
                    <a class="hastip" title="<h5>Multi Entry League</h5><p>You can rejoin these league with more then 1 Team for a particular Match/round.</p>"><i class="fa fa-users" aria-hidden="true"></i></a>
                </div>
                <?php } ?>
                <?php if($leagueVal['league']->isConfirmed){ ?>
                <div class="confirm-league">
                    <a class="hastip" title="<h5>Confirmed League</h5><p>These League won't cancel even if they don't fill up.</p>">C</a>
                </div>
                <?php } ?>
            </div>
        </div>

    </div>   
</div>
<?php } ?>
<?php if(empty($leagues)){ ?>
<div class="no-result">
    No results found. Try something else!
</div>
<?php } ?>
