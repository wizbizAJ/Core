<h4 class="mainTitle no-margin"><?php echo $pageTitle; ?></h4>
<span class="mainDescription"><?php echo @$pageTitleSubHeading; ?></span>

<ul class="pull-right breadcrumb">
    <?php
    foreach ($breadcrumb as $key => $value) {
        if (!empty($value)) {
            echo '<li>';
            echo '<a href="' . $value . '">' . $key . '</a>';
            echo '</li>';
        } else {
            echo '<li>';
            echo $key;
            echo '</li>';
        }
    }
    ?>
</ul>