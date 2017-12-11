<!-- start: FIRST SECTION -->
<div class="container-fluid container-fullw">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">    
                    <form action="<?php echo base_url(); ?>panel/league/updateLeague/<?php echo $league->id; ?>" id="leagueForm" autocomplete="off" method="post" enctype="multipart/form-data" role="form">
                        <?php
                        $flash_error = $this->session->flashdata('error');
                        $flash_success = $this->session->flashdata('success');
                        if(!empty($flash_success))
                        {
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
                        <?php } 

                        if(!empty($flash_error))
                        {
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
                        
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>
                                        League Info
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label> Title <span class="symbol required"></span> </label>
                                                <input type="text" placeholder="Enter Wining price" name="title" id="title" class="form-control" value="<?php echo $league->title;  ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Join Fees <span class="symbol required"></span> </label>
                                                <input type="text" placeholder="Enter join fees of league" name="joinFees" id="joinFees" class="form-control" value="<?php echo $league->joinFees; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Maximum Team Join <span class="symbol required"></span> </label>
                                                <input type="text" placeholder="Enter how many team join this league" name="maxTeam" id="maxTeam" class="form-control" value="<?php echo $league->maxTeam;  ?>">
                                            </div>
                                        </div>                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Win Price <span class="symbol required"></span> </label>
                                                <input type="text" placeholder="Enter win price" name="winPrice" id="winPrice" class="form-control" value="<?php echo $league->winPrice;  ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Total Winners <span class="symbol required"></span> </label>
                                                <input type="text" placeholder="Enter how many winners" name="totalWinners" id="totalWinners" class="form-control" value="<?php echo $league->totalWinners;  ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Multi Entry League </label>
                                                <select class="cs-select cs-skin-elastic" name="isMultiEntry" id="isMultiEntry">
                                                    <option value="1" <?php if($league->isMultiEntry == 1){ ?> selected="selected" <?php } ?> >Yes</option>
                                                    <option value="0" <?php if($league->isMultiEntry == 0){ ?> selected="selected" <?php } ?> >No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Confirmed League </label>
                                                <select class="cs-select cs-skin-elastic" name="isConfirmed" id="isConfirmed">
                                                    <option value="1" <?php if($league->isConfirmed == 1){ ?> selected="selected" <?php } ?> >Yes</option>
                                                    <option value="0" <?php if($league->isConfirmed == 0){ ?> selected="selected" <?php } ?> >No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Active </label>
                                                <select class="cs-select cs-skin-elastic" name="isActive" id="isActive">
                                                        <option value="1" <?php if($league->isActive == 1){ ?> selected="selected" <?php } ?> >Yes</option>
                                                        <option value="0" <?php if($league->isActive == 0){ ?> selected="selected" <?php } ?> >No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        <!-- </div>                      
                        <div class="row"> -->
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>
                                        Winners Price
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div id="exampleTableAddToolbar">
                                                <a class="btn btn-green add-row" href="javascript:void(0)" id="exampleTableAddBtn">
                                                    <i class="fa fa-plus"></i> Add more price
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 8px;">
                                        <div class="col-md-12">
                                            <table class="table table-hover" id="exampleTableAdd">
                                                <thead>
                                                    <tr>
                                                      <th>Rank</th>
                                                      <th>Prize</th>
                                                      <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($leagueWinner as $key => $val){ ?>
                                                    <tr>
                                                        <td>
                                                            <div class="form-group" style="width: 100%">
                                                                <input type="text" class="form-control" value="<?php echo $val->rank; ?>" name="rank[]" style="width: 100%">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group" style="width: 100%">
                                                                <input type="text" class="form-control" value="<?php echo $val->prize; ?>" name="prize[]" style="width: 100%">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php if($key>0){ ?>
                                                            <a href="javascript:void(0);" class="btn btn-transparent btn-xs prizeRemove"  data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-times-circle-o text-danger"></i></a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary pull-right" type="submit">
                                        Update <i class="fa fa-arrow-circle-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end: FIRST SECTION -->