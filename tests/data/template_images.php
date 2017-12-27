var page = new WebPage();
page.settings.javascriptEnabled = true;
page.onResourceTimeout = function(request) {
    phantom.exit();
};
page.settings.resourceTimeout = 30000;

page.open('https://github.com', function () {
            waitFor(function() {
            return page.evaluate(function() {
                for(var i = 0; i < document.images.length; i++) {
                    if (!document.images[i].complete) {
                        return false;
                    }
                }
                return true;
            });
        }, function() {
            savePage();
            phantom.exit();
        }, 30000);
    });

function savePage() {
    page.viewportSize = {
        width: 1200,
        height: 800    };
            page.clipRect = {
            width: 1200,
            height: 800        }
        page.render('tests/tmp/github.png');
}

function waitFor(testFx, onReady, timeOutMillis) {
    var maxtimeOutMillis = timeOutMillis ? timeOutMillis : 3000,
        start = new Date().getTime(),
        condition = false,
        interval = setInterval(function() {
            if ((new Date().getTime() - start < maxtimeOutMillis) && !condition) {
                // If not time-out yet and condition not yet fulfilled
                condition = (typeof(testFx) === "string" ? eval(testFx) : testFx()); //< defensive code
            } else {
                if (!condition) {
                    // If condition still not fulfilled (timeout but condition is 'false')
                    phantom.exit(1);
                } else {
                    // Condition fulfilled (timeout and/or condition is 'true')
                    typeof(onReady) === "string" ? eval(onReady) : onReady(); //< Do what it's supposed to do once the condition is fulfilled
                    clearInterval(interval); //< Stop this interval
                }
            }
        }, 250);
};