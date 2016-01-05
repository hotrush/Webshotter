var page = new WebPage();

page.open('https://github.com', function (status) {
    page.viewportSize = {
        width: 1200,
        height: 800    };
        page.clipRect = {
        width: 1200,
        height: 800    }
        page.render('tests/tmp/github.png');
    phantom.exit();
});

