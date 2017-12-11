<script src="<?php echo base_url(); ?>assets/components/jquery.maskedinput/dist/jquery.maskedinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/autosize/dist/autosize.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/select2/dist/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<script src="<?php echo base_url(); ?>assets/js/selectFx/classie.js"></script>
<script src="<?php echo base_url(); ?>assets/js/selectFx/selectFx.js"></script>
<script src="<?php echo base_url(); ?>assets/js/form-elements.js"></script>

<script src="<?php echo base_url(); ?>assets/components/bootstrap-jasny/dist/js/jasny-bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>assets/components/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/components/ckeditor/adapters/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/components/bb-jquery-validation/dist/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/form-validation.js"></script>

<script src="<?php echo base_url(); ?>assets/components/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/components/DataTables/media/js/dataTables.bootstrap.min.js"></script>

<script>
    jQuery(document).ready(function () {
        FormElements.init();
    });

    jQuery(document).ready(function () {

        [].slice.call(document.querySelectorAll('select.cs-select')).forEach(function (el) {
            new SelectFx(el, {
                onChange: function (val) {

                }
            });
        });

        var t = $('#exampleTableAdd').DataTable({
            "bFilter": false,
            "bPaginate": false,
            "bInfo": false,
            "bSort": false,
            "bAutoWidth": false,
            'aoColumns': [
                {"sWidth": "55%"},
                {"sWidth": "40%"},
                {"sWidth": "5%"}
            ]
        });

        $('#exampleTableAddBtn').on('click', function () {

            var row = $('#exampleTableAdd').find('tr').length;

            var rank = '<div class="form-group" style="width: 100%"><input type="text" class="form-control" value="" name="rank[]" style="width: 100%"></div>';
            var prize = '<div class="form-group" style="width: 100%"><input type="text" class="form-control" value="" name="prize[]" style="width: 100%"></div>';
            var $addedRow = t.row.add([
                rank,
                prize,
                '<a href="javascript:void(0);" class="btn btn-transparent btn-xs prizeRemove"  data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-times-circle-o text-danger"></i></a>'
            ]).draw();

            $('[data-toggle="tooltip"]').tooltip();

        });

        $('body').on('click', '.prizeRemove', function () {
            t.row($(this).closest('tr')).remove().draw();
        });


        var form1 = $('#playerForm');
        var errorHandler1 = $('.errorHandler', form1);
        var successHandler1 = $('.successHandler', form1);

        $('#playerSearchForm').validate({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.attr("name") == "dd" || element.attr("name") == "mm" || element.attr("name") == "yyyy") {
                    error.insertAfter($(element).closest('.form-group').children('div'));
                } else if (element.attr("name") == "playerId") {
                    error.insertAfter($(element).closest('.form-group').children('div'));
                } else {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
                }
            },
            ignore: "",
            rules: {
                playerId: {
                    required: true,
                    digits: true
                }
            },
            messages: {
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                successHandler1.hide();
                errorHandler1.show();
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
            onChange: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                successHandler1.show();
                errorHandler1.hide();
                $(form).find(":submit").html('Submitting <i class="fa fa-spinner fa-spin"></i>');

                //form.submit();
                $(".playerDetails").addClass('csspinner');
                $(".playerDetails").html('');
                var playerId = $("#playerId").val();
                $.ajax({
                    url: "<?php echo base_url(); ?>panel/players/getPlayerDetailByAPI",
                    data: {playerId: playerId},
                    type: "POST",
                    success: function (result) {
                        $(".playerDetails").html(result);
                        $(".playerDetails").slideDown('slow');
                        $(".playerDetails").removeClass('csspinner');
                        $(form).find(":submit").html('Search');
                    }
                });

                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }               
            }
        });        
    });
</script>