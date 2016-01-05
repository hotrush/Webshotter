var page = new WebPage();

page.open('<?php echo $url; ?>', function (status) {
    page.viewportSize = {
        width: <?php echo $width; ?>,
        height: <?php echo $height; ?>
    };
    <?php if (!$fullPage) { ?>
    page.clipRect = {
        width: <?php echo $width; ?>,
        height: <?php echo $height; ?>
    }
    <?php } ?>
    page.render('<?php echo $path; ?>');
    phantom.exit();
});

