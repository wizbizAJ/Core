<?php if (empty($customerTeam)) { ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">First things first</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-unstyled">
                    <li>Step 1: Create your team.</li>
                    <li>Step 2: Join a league!</li>
                </ul>
            </div>
            <div class="col-md-12 text-center">
                <a class="btn" href="<?php echo base_url(); ?>myteam/<?php echo $matchId; ?>">Make Your Team</a>
            </div>
        </div>
    </div>
<?php }else if($leagueInfo[0]['league']->maxTeam == count($leagueInfo[0]['teamsJoin'])){ ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">League Full</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 text-center">
                The league is already full! Join another league.
            </div>
        </div>
    </div>
<?php }else if($leagueInfo[0]['league']->joinFees == 0){ ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Final conﬁrmation and you’re in!</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">                
                Account Balance
            </div>
            <div class="col-md-8">
                Rs.0
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                Entry Fee
            </div>
            <div class="col-md-8">
                Rs.0
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                Your Team
            </div>
            <div class="col-md-8">
                <?php 
                
                        ?>
                <select name="customerTeam hello" id="customerTeam">
                    <?php   
                    if(!empty($teamInfoDetail))
                    {
                    foreach($teamInfoDetail as $cKey => $cVal){ 
                        ?>
                    <?php if(in_array($cVal->id,$joinLeagueIds)){?>
                    <option value="<?php echo $cVal->id; ?>"  >Team #<?php echo $cKey+1; ?></option>
                    <?php } ?>
                    <?php } 
                    }
                    
                    ?>
                </select>
            </div>
        </div>
        
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-12 text-center">
                <a class="btn saveLeage" href="javascript:void(0)" id="<?php echo $id; ?>">Join League</a>
            </div>
        </div>
    </div>
<?php }else{ ?>
    <?php
        $totalBalance = $walletBalance[0]->balance;
        if($leagueInfo[0]['league']->joinFees > $totalBalance){        
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Oops! Not Enough Cash</h4>
    </div>
    <div class="modal-body">
        <form action="<?php echo base_url(); ?>wallet/addCash" method="post" name="addCashForm" id="addCashForm" >
            <div class="row">
                <div class="col-md-12 text-center">
                    <span class="btn btn-wide btn-o btn-danger btn-squared">
                            Current Balance
                            <h4><b>Rs. <?php echo number_format($totalBalance,2); ?></b></h4>
                    </span>
                    <span class="btn btn-wide btn-o btn-primary btn-squared">
                            Joining Amount 
                            <h4><b>Rs. <?php echo number_format($leagueInfo[0]['league']->joinFees,2); ?></b></h4>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center" style="margin-top: 10px; margin-bottom: 10px;">
                    You don't have enough cash in your wallet to join this league.
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function(){
                var addCashForm = $('#addCashForm');
                var errorHandlerLoginForm = $('.errorHandler', addCashForm);
                var successHandlerLoginForm = $('.successHandler', addCashForm);

                $('#addCashForm').validate({
                    errorElement: "span", // contain the error msg in a span tag
                    errorClass: 'help-block',
                    errorPlacement: function (error, element) { // render error placement for each input type
                        if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
                            error.insertAfter($(element).closest('.form-group').children('div').children().last());
                        } else if (element.attr("name") == "dd" || element.attr("name") == "mm" || element.attr("name") == "yyyy") {
                            error.insertAfter($(element).closest('.form-group').children('div'));
                        } else {
                            //error.insertAfter($(element).closest('.input-group'));
                            error.insertAfter(element);
                            // for other inputs, just perform default behavior
                        }
                    },
                    ignore: "",
                    rules: {
                        cashAmount: {
                            required: true,
                            number: true
                        }
                    },
                    messages: {
                        
                    },           
                    invalidHandler: function (event, validator) { //display error alert on form submit
                        successHandlerLoginForm.hide();
                        errorHandlerLoginForm.show();
                    },
                    highlight: function (element) {
                        $(element).closest('.help-block').removeClass('valid');
                        // display OK icon
                        $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                        // add the Bootstrap error class to the control group
                    },
                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element).closest('.form-group').removeClass('has-error');
                        // set error class to the control group
                    },
                    onChange : function (element) { // revert the change done by hightlight
                        $(element).closest('.form-group').removeClass('has-error');
                        // set error class to the control group
                    },
                    success: function (label, element) {
                        label.addClass('help-block valid');
                        // mark the current input as valid and display OK icon
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
                    },
                    submitHandler: function (form,event) {
                        event.preventDefault();
                        successHandlerLoginForm.show();
                        errorHandlerLoginForm.hide();
                        $(form).find(":submit").html('Add Cash <i class="fa fa-spinner fa-spin"></i>');
                        //form.submit();                
                        var $form = $(form);
                        var formData = new FormData(form);
                        
                        $('.commonLoader.load1').addClass('csspinner');
                        
                        $.ajax({
                            url: $form.attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: "json",
                            success: function(result) {
                                if(result.success==1)
                                {
                                    var leagueMatchId = '<?php echo $matchId.'#'.$leagueId; ?>';
                                    
                                    $.ajax({
                                        url: '<?php echo base_url(); ?>leagues/joinLeague',
                                        type: 'POST',
                                        data: 'id='+leagueMatchId,
                                        //dataType: "json",
                                        success: function(result) {
                                            $('.commonLoader.load1').removeClass('csspinner');
                                            $('.popup-info-modal').find('.modal-content').html(result);
                                        }
                                    });
                                }
                                else
                                {
                                    toastr['error']("Something went wrong.", "Error");
                                }
                            }
                        });
                    }
                });
            });
    </script>
    <?php }else{ ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Final conﬁrmation and you’re in!</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">                
                Account Balance
            </div>
            <div class="col-md-8">
                Rs. <?php echo number_format($walletBalance[0]->balance,2); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                Entry Fee
            </div>
            <div class="col-md-8">
                Rs. <?php echo number_format($leagueInfo[0]['league']->joinFees,2); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                Your Team
            </div>
            <div class="col-md-8">
               <select name="customerTeam hello2" id="customerTeam">
                    <?php  
                    if(!empty($teamInfoDetail))
                    {
                    foreach($teamInfoDetail as $cKey => $cVal){ ?>
                   <?php if(in_array($cVal->id,$joinLeagueIds)){?>
                    <option value="<?php echo $cVal->id; ?>"  >Team #<?php echo $cKey+1; ?></option>
                    <?php } ?>
                    <?php }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-12 text-center">
                <a class="btn saveLeage" href="javascript:void(0)" id="<?php echo $id; ?>">Join League</a>
            </div>
        </div>
    </div>
    <?php } ?>

<?php } ?>


