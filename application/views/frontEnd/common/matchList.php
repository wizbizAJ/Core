<div class="team-match">
<?php
foreach($match as $key => $val){
    $teams = explode('vs', $val->title);
    $url1 = 'assets/user_files/player/'.trim($teams[0]).'/'.trim($teams[0]).'.png';
    $url2 = 'assets/user_files/player/'.trim($teams[1]).'/'.trim($teams[1]).'.png';
    $url1 = file_exists($url1)?base_url().$url1:base_url().'assets/images/default-user.png';
    $url2 = file_exists($url2)?base_url().$url2:base_url().'assets/images/default-user.png';
   
   
?>
<div class="select-match" >
    <div class="slider-border <?php if($val->crickMatchId==$smatch_id){ ?>hightLightTopLeague<?php } ?>">
        <div class="col-sm-6 col-xs-6 team-1">
            <div class="team-flag marginl-15 marginr-15">
                <img src="<?php echo $url1; ?>" alt="<?php echo trim($teams[0]); ?>">
            </div>
            <div class="black-overlay"></div>
        </div>
        <div class="col-sm-6 col-xs-6 team-2">
            <div class="team-flag marginl-15 marginr-15">
                <img src="<?php echo $url2; ?>" alt="<?php echo trim($teams[1]); ?>">
            </div>
            <div class="black-overlay"></div>
        </div>
        <div class="slider-match-details">
            <div class="slider-team-name text-center">
                <div class="slider-team-flag col-sm-6 col-xs-6">
                    <img src="<?php echo $url1; ?>" alt="<?php echo trim($teams[0]); ?>">
                    <p><?php echo (!empty($val->sortName1))?$val->sortName1:trim($teams[0]); ?></p>
                </div>
                <div class="slider-team-flag col-sm-6 col-xs-6">
                    <img src="<?php echo $url2; ?>" alt="<?php echo trim($teams[1]); ?>">
                    <p><?php echo (!empty($val->sortName2))?$val->sortName2:trim($teams[1]); ?></p>
                </div>
                <div class="clearfix"></div>
                <div class="slider-vs">
                    <p>VS</p>
                </div>
            </div>
            <div class="slider-match">
                <p><?php echo $val->matchType; ?></p>
            </div>
            <div class="slider-btm">
                <div class="col-sm-6 text-center" style="padding-left: 5px;padding-right: 5px;">
                    <span><i class="fa fa-clock-o" aria-hidden="true"></i>
                        <?php
                        $currentTime = $this->setting_model->converTimeZone('d/m/Y H:i:s',date('Y-m-d H:i:s'));
                        $matchTime = $this->setting_model->converTimeZone('d/m/Y H:i:s',$val->startDateTime);
                        if($matchTime < $currentTime){
                            echo 'Close';
                        }else{
                            echo '<lable id="remainTime'.$val->crickMatchId.'"></lable>';
                        }
                        ?>
                    </span>
                </div>
                <div class="col-sm-6 slide-point">
                    <?php
                        if(!empty($customer))
                        {
                        if($matchTime < $currentTime)
                        {
                            echo '<a href="'.base_url().'summary/'.$val->crickMatchId.'">view point</a>';
                        }
                        else
                        {
                            echo '<a href="'.base_url().'leagues/'.$val->crickMatchId.'">Join</a>';
                        }
                        }else{
                         if($matchTime < $currentTime)
                        {
                            echo '<a href="javascript:void(0)" data-toggle="modal" data-target=".login-modal"  data-id="'.$val->crickMatchId.'">view point</a>';
                        }
                        else
                        {
                            echo '<a href="javascript:void(0)" data-toggle="modal" data-target=".login-modal" data-id="'.$val->crickMatchId.'" >Join</a>';
                        }   
                        }
                    ?>                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
    
</div>

<?php foreach($match as $key => $val){ 
    $currentTime = $this->setting_model->converTimeZone('d/m/Y H:i:s',date('Y-m-d H:i:s'));
    $matchTime = $this->setting_model->converTimeZone('d/m/Y H:i:s',$val->startDateTime);
    if(!($matchTime < $currentTime)){    
?>
        <script type="text/javascript">

            initializeClock<?php echo $val->crickMatchId; ?>('remainTime<?php echo $val->crickMatchId; ?>','<?php echo $this->setting_model->converTimeZone('Y-m-d H:i:s',$val->startDateTime); ?>');

            function initializeClock<?php echo $val->crickMatchId; ?>(id, endtime){
                var myteamclock<?php echo $val->crickMatchId; ?> = document.getElementById(id);
                var myteamtimeinterval<?php echo $val->crickMatchId; ?> = setInterval(function(){
                    var t = getTimeRemaining(endtime);
                    //clock.innerHTML = t.days + ' days ' + t.hours + ' hours ' + t.minutes + ' minutes ' + t.seconds + ' seconds';
                    myteamclock<?php echo $val->crickMatchId; ?>.innerHTML = t.hours + ':' + t.minutes + ':' + t.seconds;
                    if(t.total<=0){
                        myteamclock<?php echo $val->crickMatchId; ?>.innerHTML = 'Closed';
                        clearInterval(myteamtimeinterval<?php echo $val->crickMatchId; ?>);
                    }
                },1000);
            }
        </script>
<?php 
    }
} ?>