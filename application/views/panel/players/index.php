<!-- start: FIRST SECTION -->
<div class="container-fluid container-fullw">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 space20 text-right">
                            <a class="btn btn-green add-row" href="<?php echo base_url(); ?>panel/players/add">
                                Add New <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <?php
                    $flash_error = $this->session->flashdata('error');
                    $flash_success = $this->session->flashdata('success');

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

                    <table class="table table-hover" id="players">
                        <thead>
                            <tr>                               
                                <th>Player Id</th>
                                <th class="text-center">Image</th>
                                <th>Name</th>
                                <th>Major Teams</th>
                                <th>Country</th>
                                <th>Player Type</th>
                                <th>Credits</th>                                                                
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <thead class="filters">
                            <tr>                               
                                <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search" /></th>
                                <th>&nbsp;</th>
                                <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search" /></th>
                                <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search" /></th>
                                <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search" /></th>
                                <th>                                
                                    <select class="cs-select cs-skin-elastic" name="playerType" id="playerType">
                                        <option value="" >&nbsp;</option>
                                        <option value="Batsman"  >Batsman</option>
                                        <option value="Bowler"  >Bowler</option>
                                        <option value="All-rounder"  >All-rounder</option>
                                        <option value="Wicket-Keeper"  >Wicket-Keeper</option>
                                    </select>
                                </th>
                                <th>
                                    <input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search" />
                                </th>                                
                                <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search" /></th>
                                <th></th>
                            </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end: FIRST SECTION -->