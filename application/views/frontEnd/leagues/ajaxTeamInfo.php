<div class="topTeamEdit text-right">
    <a href="<?php echo base_url(); ?>myteam/update/<?php echo $team[0]['team']->id; ?>/<?php echo $team[0]['team']->crickMatchId; ?>">Edit Team</a>
    <a href="<?php echo base_url(); ?>myteam/<?php echo $team[0]['team']->crickMatchId; ?>" >Add Team #<?php echo $countTeam; ?></a>
</div>

<?php foreach ($team[0]['teamPlayers'] as $tKey => $tVal) { ?>
    <div class="team-list filter">

        <ul class="list-inline player-details text-center iconForCaption">
            <li class="player-name <?php
            if (!empty($tVal->isCaptain)) {
                echo 'caption';
            } if (!empty($tVal->isViceCaptain)) {
                echo 'vice-caption';
            }
            ?>">
                <div class="player-face">
                    <?php
                    $playerImg = 'assets/user_files/player/' . $tVal->country . '/' . $tVal->profileImage;
                    $playerImg = (!empty($tVal->profileImage) && file_exists($playerImg)) ? base_url() . $playerImg : base_url() . 'assets/images/default-user.png';
                    ?>
                    <img src="<?php echo $playerImg; ?>" alt="<?php echo $tVal->name; ?>">
                </div>
                <h5 class="<?php
                if (!empty($tVal->isCaptain)) {
                    echo 'capIco';
                } if (!empty($tVal->isViceCaptain)) {
                    echo 'vCapIco';
                }
                ?>"><?php echo $tVal->name; ?></h5>
            </li>
        </ul>
    </div>
<?php } ?>
