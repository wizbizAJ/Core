<script src="<?php echo base_url(); ?>assets/components/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/DataTables/media/js/dataTables.bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>assets/components/toastr/toastr.min.js"></script>

<script src="<?php echo base_url(); ?>assets/components/select2/dist/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/selectFx/classie.js"></script>
<script src="<?php echo base_url(); ?>assets/js/selectFx/selectFx.js"></script>



<script>
    jQuery(document).ready(function () {

        var oTable = $('#players').DataTable({
            "bFilter": true,
            "bProcessing": true,
            "bAutoWidth": false,
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url(); ?>panel/players/ajaxPlayer",
            "fnDrawCallback": function (oSettings)
            {
                //Main.init();
                $('[data-toggle="tooltip"]').tooltip();
            },
            "aaSorting": [[7, "desc"]],
            "iDisplayLength": <?php echo $showItemsPerPage; ?>,
            'aoColumns': [
                {"sWidth": "8%"},
                {"sWidth": "8%", 'class': 'text-center', 'bSortable': false},
                {"sWidth": "13%"},
                {"sWidth": "30%"},
                {"sWidth": "10%"},
                {"sWidth": "10%"},
                {"sWidth": "5%", 'class': 'text-center'},
                {"sWidth": "12%"},
                {"sWidth": "4%", 'bSortable': false}
            ]
        });

        oTable.columns().eq(0).each(function (colIdx) {
            $('input', $('.filters th')[colIdx]).on('keyup change', function () {
                oTable.column(colIdx).search(this.value).draw();
            });
        });


        [].slice.call(document.querySelectorAll('#playerType')).forEach(function (el) {
            new SelectFx(el, {
                onChange: function (val) {
                    oTable.columns(4).search(val).draw();
                }
            });
        });

        [].slice.call(document.querySelectorAll('#isActive')).forEach(function (el) {
            new SelectFx(el, {
                onChange: function (val) {
                    oTable.columns(7).search(val).draw();
                }
            });
        });

        /* Start :: Multi Action */
        $("#checkall").change(function () {
            $("input:checkbox.hasAll").prop('checked', $(this).prop("checked"));
            if ($(this).prop("checked"))
            {
                $('#dataTableAction').click();
            }
        });

        $('body').on('click', '.playerAction', function () {
            var idStr = [];
            $("input:checkbox.hasAll").each(function () {
                if ($(this).is(":checked"))
                {
                    if ($.isNumeric($(this).val()))
                    {
                        idStr.push($(this).val());
                    }
                }
            });

            if (idStr.length > 0)
            {
                var msgStr = '';
                var isMulti = 2;
                var isConfirmed = 2;
                var isActive = 2;
                var isRemove = 0;
                var confirmButtonColor = '';
                var confirmButtonText = '';
                if ($(this).hasClass('activeAllPlayer'))
                {
                    msgStr = 'This player is display after this action!';
                    confirmButtonColor = '#8cd4f5';
                    confirmButtonText = 'Yes, active plx!';
                    isActive = 1;
                } else if ($(this).hasClass('inactiveAllPlayer'))
                {
                    msgStr = 'This player is not display after this action!';
                    confirmButtonColor = '#8cd4f5';
                    confirmButtonText = 'Yes, inactive plx!';
                    isActive = 0;
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
                }, function (isConfirm) {
                    if (isConfirm) {
                        if (isMulti != 2)
                        {
                            multiEntry(oTable, idStr, isMulti);
                        } else if (isConfirmed != 2)
                        {
                            confirmed(oTable, idStr, isConfirmed);
                        } else if (isActive != 2)
                        {
                            active(oTable, idStr, isActive);
                        } else if (isRemove == 1)
                        {
                            remove(oTable, idStr);
                        }
                        $("#checkall").prop('checked', false);
                    } else {
                        swal("Cancelled", "Your player is safe :)", "error");
                    }
                });
            } else
            {
                swal("Sorry!", "Please select atleast one player :)", "error");
            }
        });
        /* End :: Multi Action */

        $('body').on('click', '.isActive', function () {
            var playerId = $(this).attr('id');
            swal({
                title: "Are you sure?",
                text: "This player is not display after this action!",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#8cd4f5",
                confirmButtonText: "Yes, inactive plx!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    active(oTable, playerId, 0);
                } else {
                    swal("Cancelled", "Your Player is active :)", "error");
                }
            });
        });
        $('body').on('click', '.inActive', function () {
            var playerId = $(this).attr('id');
            swal({
                title: "Are you sure?",
                text: "This player is display after this action!",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#8cd4f5",
                confirmButtonText: "Yes, active plx!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    active(oTable, playerId, 1);
                } else {
                    swal("Cancelled", "Your Player is inactive :)", "error");
                }
            });
        });

    });

    function active(oTable, id, val)
    {
        $.ajax({
            url: "<?php echo base_url(); ?>panel/players/updateIsActive",
            data: {id: id, isActive: val},
            type: "POST",
            success: function (result) {
                swal("Updated!", "Player has been updated.", "success");
                oTable.draw();
            }
        });
    }
</script>