<script type="text/javascript">

<?php if (!empty($myLeagues)) { ?>
        teamInfoData('<?php echo $myLeagues[0]['joinLeague']->customerTeamId . '#' . $myLeagues[0]['joinLeague']->leagueId . '#' . $myLeagues[0]['joinLeague']->id; ?>');
<?php } ?>

    $('.getTeamStatistic').on('click', function () {
        var id = $(this).attr('id');
        teamInfoData(id);
    });

    function teamInfoData(id)
    {
        $('.commonLoader.load1').addClass('csspinner');
        $.ajax({
            url: "<?php echo base_url(); ?>summary/ajaxTeamInfoMy",
            data: {id: id},
            type: "POST",
            success: function (result) {
                $('.commonLoader.load1').removeClass('csspinner');

                $('.teamInfoStatistic').slideUp('slow', function () {
                    $('.teamInfoStatistic').html(result);
                    $('.teamInfoStatistic').slideDown('slow');
                });
            }
        });
    }

</script>