<section class="container inner-mid"> 
    <div class="tab-menu col-sm-12 col-md-6 paddingl-none"> 
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="col-sm-12 col-xs-4 text-center paddingl-none paddingr-none">
                <a href="#myleagues" aria-controls="My Leagues" role="tab" data-toggle="tab">
                    <?php echo $scorecard['info']['mathName']; ?>
                    <small> (<?php echo $this->setting_model->converTimeZone('dS F Y, h:i A', $scorecard['info']['startDateTime']); ?>) </small>
                </a>
            </li>
        </ul>
    </div>
    <div class="clearfix"></div>
    <!-- Tab panes -->
    <div class="my-leagues">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="myleagues">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr style="font-weight: 600">
                            <th>Player Name</th>
                            <th class="text-center" colspan="4">Batting</th>
                            <th class="text-center" >Bowling</th>
                            <th>Catch</th>
                            <th>Total</th>

                        </tr>
                        <tr style="font-weight: 600">
                            <th></th>
                            <th>Runs</th>
                            <th>4's</th>
                            <th>6's</th>
                            <th>Duck</th>
                            <th>Wkts</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($scorecard['otherInfo'])) {
                            foreach ($scorecard['otherInfo'] as $key => $value) {
                                ?>
                                <tr>
                                    <td><?php echo!empty($value['name']) ? $value['name'] : "-" ?></td>
                                    <td><?php echo!empty($value['details']['batting']['Runs']) ? $value['details']['batting']['Runs'] : "0" ?></td>
                                    <td><?php echo!empty($value['details']['batting']['4s']) ? $value['details']['batting']['4s'] : "0" ?></td>
                                    <td><?php echo!empty($value['details']['batting']['6s']) ? $value['details']['batting']['6s'] : "0" ?></td>
                                    <td><?php echo!empty($value['details']['batting']['duck']) ? $value['details']['batting']['duck'] : "0" ?></td>
                                    <td><?php echo!empty($value['details']['bowling']['Wkts']) ? $value['details']['bowling']['Wkts'] : "0" ?></td>
                                    <td><?php echo!empty($value['details']['catch']) ? $value['details']['catch'] : "0" ?></td>
                                    <td><?php echo!empty($value['details']['total']) ? $value['details']['total'] : "0" ?></td>
                                </tr>
                                <?php
                            } ?>
                           <tr style="font-weight: bold;">
                            <td colspan="7" class="text-right">Total</td>
                            <td colspan="8"><?php echo $scorecard['info']['total']; ?></td>
                           </tr> 
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>