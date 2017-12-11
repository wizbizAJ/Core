<script src="<?php echo base_url(); ?>assets/components/toastr/toastr.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontEnd/js/tooltipsy.min.js"></script>

<script type="text/javascript">
    
    toastr.options = {
        "closeButton": false,
        "positionClass": "toast-bottom-right",
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000"
    }
    
    loadLeage('<?php echo $match->crickMatchId; ?>');
    
    $('.winFillter').on('change',function(){
        var winFillter = $(this).val();
        var payFillter = $('.payFillter').val();
        var sizeFillter = $('.sizeFillter').val();
                
        loadLeage('<?php echo $match->crickMatchId; ?>',winFillter,payFillter,sizeFillter);
    });
    
    $('.payFillter').on('change',function(){
        var winFillter = $('.winFillter').val();
        var payFillter = $(this).val();
        var sizeFillter = $('.sizeFillter').val();
        
        loadLeage('<?php echo $match->crickMatchId; ?>',winFillter,payFillter,sizeFillter);
    });
    
    $('.sizeFillter').on('change',function(){
        var winFillter = $('.winFillter').val();
        var payFillter = $('.payFillter').val();
        var sizeFillter = $(this).val();
        
        loadLeage('<?php echo $match->crickMatchId; ?>',winFillter,payFillter,sizeFillter);
    });
    
    $('.refreshFillter').on('click',function(){
        location.reload();
    });
    
    function loadLeage(match='',win='',pay='',size='')
    {    
        $('.commonLoader.load1').addClass('csspinner');        
        $.ajax({
            url: "<?php echo base_url(); ?>leagues/ajaxList",
            data:{matchId: match,winFillter:win,payFillter:pay,sizeFillter:size},
            type: "POST",
            success: function(result){ 
                $('.commonLoader.load1').removeClass('csspinner');
                $('#Public').html(result);
                $('.hastip').tooltipsy({
                    offset: [-1, 10],
                    css: {
                        'padding': '10px',
                        'max-width': '250px',
                        'color': '#333',
                        'background-color': '#F7F7F7',
                        'border': '1px solid #F7F7F7',
                        '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                        '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                        'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                        'text-shadow': 'none'
                    }
                });
            }
        });
    }
    
    $(function() {
        /* start : Click on Join League btn */
        $('body').on('click','.joinNow',function(){
            var id = $(this).attr('id');
            $('.popup-info-modal').modal();
            $('.popup-info-modal').find('.load1').addClass('csspinner');
            $.ajax({
                url: '<?php echo base_url(); ?>leagues/joinLeague',
                type: 'POST',
                data: 'id='+id,
                //dataType: "json",
                success: function(result) {
                    $('.popup-info-modal').find('.load1').removeClass('csspinner');
                    $('.popup-info-modal').find('.modal-content').html(result);
                }
            });
        });
        /* End : Click on Join League btn */
        
        /* Start : Click on Join League Final Confirmation */
        $('body').on('click','.saveLeage',function(){
            var id = $(this).attr('id');
            var teamId = $(this).parents('.modal-body').find('#customerTeam').val();
            
            $('.commonLoader.load1').addClass('csspinner');
            $.ajax({
                url: '<?php echo base_url(); ?>leagues/saveLeague',
                type: 'POST',
                data: 'id='+id+'&customerTeamId='+teamId,
                //dataType: "json",
                success: function(result) {
                    location.reload();
                }
            });
        });
        /* End : Click on Join League Final Confirmation */
        
        /* Start : Click on Join League Final Confirmation */
        $('body').on('click','.saveLeagueTeam',function(){
            var id = $(this).attr('id');
            var teamId = $(this).parent('div').parent('li').find('.leagueTeamId').val();
            
            $('.commonLoader.load1').addClass('csspinner');
            $.ajax({
                url: '<?php echo base_url(); ?>leagues/saveMyLeague',
                type: 'POST',
                data: 'id='+id+'&teamId='+teamId,
                //dataType: "json",
                success: function(result) {
                    $('.commonLoader.load1').removeClass('csspinner');
                    toastr['success']("Team Change successfully.", "Success");
                }
            });
        });
        /* End : Click on Join League Final Confirmation */
        
        /* Start : Add more cash */
        $('body').on('click','.addmore400',function(){
            $('#cashAmount').val(parseFloat($('#cashAmount').val())+parseFloat(400));
        });
        $('body').on('click','.addmore200',function(){
            $('#cashAmount').val(parseFloat($('#cashAmount').val())+parseFloat(200));
        });
        $('body').on('click','.addmore100',function(){
            $('#cashAmount').val(parseFloat($('#cashAmount').val())+parseFloat(100));
        });
        /* End : Add more cash */        
    });
</script>
<!-- Add For Header-->
<script type="text/javascript">
    
    <?php if (!empty($teamInfo)) { ?>
        teamInfoData('<?php echo $teamInfo[0]['team']->id;?>#<?php echo $teamInfo[0]['team']->crickMatchId;?>');
    <?php } ?>
    
    $('.getTeamStatistic').on('click', function () {
        $( ".teamItem" ).removeClass("active");
        $(this).parent( ".teamItem" ).addClass("active");
        var id = $(this).attr('id');
        teamInfoData(id);        
    });
    
    function teamInfoData(id)
    {
        $('.commonLoader.load1').addClass('csspinner');
        $.ajax({
            url: "<?php echo base_url(); ?>leagues/ajaxTeamInfo",
            data: {id: id},
            type: "POST",
            success: function (result) {                
                $('.commonLoader.load1').removeClass('csspinner');                                
                $('.cutomerOwnTeam').slideUp('slow',function(){
                    $('.cutomerOwnTeam').html(result);
                    $('.cutomerOwnTeam').slideDown('slow');                    
                });
            }
        });
    }
    
</script>
<!-- Add For Header-->