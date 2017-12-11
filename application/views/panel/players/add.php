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

                    <div class="row">
                        <div class="col-md-12">
                            <form  id="playerSearchForm" autocomplete="off" method="post" enctype="multipart/form-data" role="form">
                                <div class="row">
                                    <div class="col-md-12">                                            
                                        <div class="form-group">                                                   
                                            <div class="input-group">                                                   
                                                <input type="text" id="playerId"  placeholder="Enter Player Id" name="playerId"  class="form-control" value="<?php echo @$postData['playerId']; ?>">                                                    
                                                <span class="input-group-addon searchPlayer" ><button type="submit" class="btn btn-primary pull-right searchbtn" >Search</button></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>     
                        </div>
                    </div>
                    <div class="playerDetails load1" ></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end: FIRST SECTION -->