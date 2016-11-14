var page = new WebPage();
page.settings.javascriptEnabled = true;
page.onResourceTimeout = function(request) {
    phantom.exit();
};
page.settings.resourceTimeout = <?php echo $timeout; ?>;

page.open('<?php echo $url; ?>', function () {
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

