<!-- start: FIRST SECTION -->
<div class="container-fluid container-fullw">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 space20 text-right">
                            <a class="btn btn-green add-row" href="<?php echo base_url(); ?>panel/league/add">
                                Add New <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
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
                    
                    <table class="table table-hover" id="leagues">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox clip-check check-purple">
                                        <input type="checkbox" id="checkall" value="1">
                                        <label for="checkall" class="btn-group my-btn-group">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dataTableAction"> <span class="caret"></span> </a>
                                            <ul role="menu" class="dropdown-menu dropdown-light pull-left">
                                                <li class="dropdown-submenu">
                                                    <a href="javascript:void(0)">Multi Entry</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0)" class="leagueAction multiAllLeague"><i class="fa fa-check-circle-o text-success"></i> Yes</a></li>
                                                        <li><a href="javascript:void(0)" class="leagueAction notMultiAllLeague"><i class="fa fa-check-circle-o text-danger"></i> No</a></li>
                                                    </ul>
                                                </li>
                                                <li class="dropdown-submenu">
                                                    <a href="javascript:void(0)">Confirmed League</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0)" class="leagueAction confirmedAllLeague"><i class="fa fa-check-circle-o text-success"></i> Yes</a></li>
                                                        <li><a href="javascript:void(0)" class="leagueAction notConfirmedAllLeague"><i class="fa fa-check-circle-o text-danger"></i> No</a></li>
                                                    </ul>
                                                </li>
                                                <li class="dropdown-submenu">
                                                    <a href="javascript:void(0)">Is Active</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0)" class="leagueAction activeAllLeague"><i class="fa fa-check-circle-o text-success"></i> Active</a></li>
                                                        <li><a href="javascript:void(0)" class="leagueAction inactiveAllLeague"><i class="fa fa-check-circle-o text-danger"></i> Inactive</a></li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="leagueAction removeAllLeague"> <i class="fa fa-times fa fa-white"></i> Remove </a>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>
                                </th>
                                <th>Title</th>
                                <th>Pay Rs.</th>
                                <th>Max Teams Join</th>
                                <th>Multi Entry</th>
                                <th>Confirmed League</th>
                                <th>Active</th>
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <thead class="filters">
                            <tr>
                              <th>&nbsp;</th>
                              <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search Title" /></th>
                              <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search Pay Rs." /></th>
                              <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search Max Teams" /></th>
                              <th>
                                <select class="cs-select cs-skin-elastic" name="isMultiEntry" id="isMultiEntry">
                                    <option value="" data-class="fa fa-times-circle-o" >&nbsp;</option>
                                    <option value="1" data-class="fa fa-check-circle-o text-success" >Yes</option>
                                    <option value="0" data-class="fa fa-check-circle-o text-danger" >No</option>
                                </select>
                              </th>
                              <th>
                                <select class="cs-select cs-skin-elastic" name="isConfirmed" id="isConfirmed">
                                    <option value="" data-class="fa fa-times-circle-o" >&nbsp;</option>
                                    <option value="1" data-class="fa fa-check-circle-o text-success" >Confirmed</option>
                                    <option value="0" data-class="fa fa-check-circle-o text-danger" >Not Confirmed</option>
                                </select>
                              </th>
                              <th>
                                <select class="cs-select cs-skin-elastic" name="isActive" id="isActive">
                                    <option value="" data-class="fa fa-times-circle-o" >&nbsp;</option>
                                    <option value="1" data-class="fa fa-check-circle-o text-success" >Active</option>
                                    <option value="0" data-class="fa fa-check-circle-o text-danger" >Inactive</option>
                                </select>
                              </th>
                              <th><input class="form-control" style="width:100%;font-weight:100;" type="text" placeholder="Search Updated At" /></th>
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