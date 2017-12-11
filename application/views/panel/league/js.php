<script src="<?php echo base_url(); ?>assets/components/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/DataTables/media/js/dataTables.bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>assets/components/toastr/toastr.min.js"></script>

<script src="<?php echo base_url(); ?>assets/components/select2/dist/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/selectFx/classie.js"></script>
<script src="<?php echo base_url(); ?>assets/js/selectFx/selectFx.js"></script>



<script>    
    jQuery(document).ready(function() {
    
        var oTable = $('#leagues').DataTable({
            "bFilter": true,
            "bProcessing": true,
            "bAutoWidth": false,
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url(); ?>panel/league/ajaxLeague",
            "fnDrawCallback": function(oSettings)
            {
                //Main.init();
                $('[data-toggle="tooltip"]').tooltip();
            },
            "aaSorting": [ [7, "desc"] ],
            "iDisplayLength": <?php echo $showItemsPerPage; ?>,
            'aoColumns': [
                {"sWidth": "5%",'bSortable': false},
                {"sWidth": "20%"},
                {"sWidth": "10%"},
                {"sWidth": "12%"},
                {"sWidth": "10%",'class': 'text-center'},
                {"sWidth": "13%",'class': 'text-center'},
                {"sWidth": "10%",'class': 'text-center'},
                {"sWidth": "12%"},
                {"sWidth": "8%",'bSortable': false}
            ]
        });
        
        oTable.columns().eq( 0 ).each( function ( colIdx ) {
            $( 'input', $('.filters th')[colIdx] ).on( 'keyup change', function () {
                oTable.column( colIdx ).search( this.value ).draw();
            });
        });
        
        [].slice.call(document.querySelectorAll('#isMultiEntry')).forEach(function(el) {
            new SelectFx(el,{
                onChange: function(val) {
                    oTable.columns(4).search(val).draw();
                }
            });
        });
        
        [].slice.call(document.querySelectorAll('#isConfirmed')).forEach(function(el) {
            new SelectFx(el,{
                onChange: function(val) {
                    oTable.columns(5).search(val).draw();
                }
            });
        });
        
        [].slice.call(document.querySelectorAll('#isActive')).forEach(function(el) {
            new SelectFx(el,{
                onChange: function(val) {
                    oTable.columns(6).search(val).draw();
                }
            });
        });
        
        /* Start :: Multi Action */
        $("#checkall").change(function () {
            $("input:checkbox.hasAll").prop('checked', $(this).prop("checked"));
            if($(this).prop("checked"))
            {
              $('#dataTableAction').click();
            }
        });
        
        $('body').on('click','.leagueAction',function(){
            var idStr=[];
            $("input:checkbox.hasAll").each(function(){
                if($(this).is(":checked"))
                {
                    if($.isNumeric($(this).val()))
                    {
                        idStr.push($(this).val());
                    }
                }
            });

            if(idStr.length > 0)
            {
                var msgStr = '';
                var isMulti = 2;
                var isConfirmed = 2;
                var isActive = 2;
                var isRemove = 0;
                var confirmButtonColor='';
                var confirmButtonText='';

                if($(this).hasClass('multiAllLeague'))
                {
                    msgStr = 'Customer can regoin this league after this action!';
                    confirmButtonColor = '#8cd4f5';
                    confirmButtonText = 'Yes, multi entry plx!';
                    isMulti = 1;
                }
                else if($(this).hasClass('notMultiAllLeague'))
                {
                    msgStr = 'Customer can not regoin this league after this action!';
                    confirmButtonColor = '#8cd4f5';
                    confirmButtonText = 'Yes, not multi entry plx!';
                    isMulti = 0;
                }
                else if($(this).hasClass('confirmedAllLeague'))
                {
                    msgStr = 'This league won\'t cancel after this action!';
                    confirmButtonColor = '#8cd4f5';
                    confirmButtonText = 'Yes, confirmed plx!';
                    isConfirmed = 1;
                }
                else if($(this).hasClass('notConfirmedAllLeague'))
                {
                    msgStr = 'This league may be cancel after this action!';
                    confirmButtonColor = '#8cd4f5';
                    confirmButtonText = 'Yes, not confirmed plx!';
                    isConfirmed = 0;
                }
                else if($(this).hasClass('activeAllLeague'))
                {
                    msgStr = 'This league is display after this action!';
                    confirmButtonColor = '#8cd4f5';
                    confirmButtonText = 'Yes, active plx!';
                    isActive = 1;
                }
                else if($(this).hasClass('inactiveAllLeague'))
                {
                    msgStr = 'This league is not display after this action!';
                    confirmButtonColor = '#8cd4f5';
                    confirmButtonText = 'Yes, inactive plx!';
                    isActive = 0;
                }
                else if($(this).hasClass('removeAllLeague'))
                {
                    msgStr = 'You will not be able to recover this league!'
                    confirmButtonColor = '#DD6B55';
                    confirmButtonText = 'Yes, delete it!';
                    isRemove = 1;
                }
                
                swal({
                    title: "Are you sure?",
                    text: msgStr,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {
                    if(isConfirm) {
                        if(isMulti != 2)
                        {
                            multiEntry(oTable,idStr,isMulti);
                        }
                        else if(isConfirmed != 2)
                        {
                            confirmed(oTable,idStr,isConfirmed);
                        }
                        else if(isActive != 2)
                        {
                            active(oTable,idStr,isActive);
                        }
                        else if(isRemove == 1)
                        {
                            remove(oTable,idStr);
                        }
                        $("#checkall").prop('checked', false);
                    } else {
                        swal("Cancelled", "Your league is safe :)", "error");
                    }
                });
            }
            else
            {
                swal("Sorry!", "Please select atleast one league :)", "error");
            }
        });        
        /* End :: Multi Action */
        
                
        $('body').on('click','.removeLeague',function(){
            var leagueId=$(this).attr('id');            
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this league!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
            }, function(isConfirm) {
                    if(isConfirm) {
                        remove(oTable,leagueId);
                    } else {
                        swal("Cancelled", "Your League is safe :)", "error");
                    }
            });
        });
        
        $('body').on('click','.activeMultiEntry',function(){
            var leagueId=$(this).attr('id');            
            swal({
                    title: "Are you sure?",
                    text: "Customer can not regoin this league after this action!",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Yes, not multi entry plx!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
            }, function(isConfirm) {
                    if(isConfirm) {
                        multiEntry(oTable,leagueId,0);
                    } else {
                        swal("Cancelled", "Your League is multi entry league :)", "error");
                    }
            });
        });
        $('body').on('click','.inactiveMultiEntry',function(){
            var leagueId=$(this).attr('id');            
            swal({
                    title: "Are you sure?",
                    text: "Customer can regoin this league after this action!",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Yes, multi entry plx!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
            }, function(isConfirm) {
                    if(isConfirm) {
                        multiEntry(oTable,leagueId,1);
                    } else {
                        swal("Cancelled", "Your League is not multi entry league :)", "error");
                    }
            });
        });
        
        $('body').on('click','.isConfirmed',function(){
            var leagueId=$(this).attr('id');            
            swal({
                    title: "Are you sure?",
                    text: "This league may be cancel after this action!",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Yes, not confirmed plx!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
            }, function(isConfirm) {
                    if(isConfirm) {
                        confirmed(oTable,leagueId,0);
                    } else {
                        swal("Cancelled", "Your League is confirmed league :)", "error");
                    }
            });
        });
        $('body').on('click','.isNotConfirmed',function(){
            var leagueId=$(this).attr('id');            
            swal({
                    title: "Are you sure?",
                    text: "This league won't cancel after this action!",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Yes, confirmed plx!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
            }, function(isConfirm) {
                    if(isConfirm) {
                        confirmed(oTable,leagueId,1);
                    } else {
                        swal("Cancelled", "Your League is not confirmed league :)", "error");
                    }
            });
        });
        
        $('body').on('click','.isActive',function(){
            var leagueId=$(this).attr('id');            
            swal({
                    title: "Are you sure?",
                    text: "This league is not display after this action!",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Yes, inactive plx!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
            }, function(isConfirm) {
                    if(isConfirm) {
                        active(oTable,leagueId,0);
                    } else {
                        swal("Cancelled", "Your League is active :)", "error");
                    }
            });
        });
        $('body').on('click','.inActive',function(){
            var leagueId=$(this).attr('id');            
            swal({
                    title: "Are you sure?",
                    text: "This league is display after this action!",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#8cd4f5",
                    confirmButtonText: "Yes, active plx!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false
            }, function(isConfirm) {
                    if(isConfirm) {
                        active(oTable,leagueId,1);
                    } else {
                        swal("Cancelled", "Your League is inactive :)", "error");
                    }
            });
        });
        
    });
    
    
function remove(oTable,leagueId)
{
    $.ajax({
        url: "<?php echo base_url(); ?>panel/league/remove",
        data:{id:leagueId},
        type: "POST",
        success: function(result){
            swal("Deleted!", "League has been deleted.", "success");
            oTable.draw();
        }
    });
}

function multiEntry(oTable,id,val)
{
    $.ajax({
        url: "<?php echo base_url(); ?>panel/league/updateMultyEntry",
        data:{id:id,isMultiEntry:val},
        type: "POST",
        success: function(result){
            swal("Updated!", "League has been updated.", "success");
            oTable.draw();
        }
    });
}

function confirmed(oTable,id,val)
{
    $.ajax({
        url: "<?php echo base_url(); ?>panel/league/updateConfirmed",
        data:{id:id,isConfirmed:val},
        type: "POST",
        success: function(result){
            swal("Updated!", "League has been updated.", "success");
            oTable.draw();
        }
    });
}

function active(oTable,id,val)
{
    $.ajax({
        url: "<?php echo base_url(); ?>panel/league/updateIsActive",
        data:{id:id,isActive:val},
        type: "POST",
        success: function(result){
            swal("Updated!", "League has been updated.", "success");
            oTable.draw();
        }
    });
}
</script>