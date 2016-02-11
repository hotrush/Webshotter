var page = new WebPage();
page.settings.javascriptEnabled = true;
page.onResourceTimeout = function(request) {
    phantom.exit();
};
page.settings.resourceTimeout = 30000;

page.open('https://github.com', function () {
    page.viewportSize = {
        width: 1200,
        height: 800    };
        page.clipRect = {
        width: 1200,
        height: 800    }
        page.render('tests/tmp/github.png');
    phantom.exit();
});

