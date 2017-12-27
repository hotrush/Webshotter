<?php

use hotrush\Webshotter\Webshot;

class TimeoutTest extends PHPUnit_Framework_TestCase
{
    public function testTimeout()
    {
        $path = realpath(dirname(__FILE__).'/../tests/tmp/');
        $webshot = new Webshot();
        $this->setExpectedException(
            'hotrush\Webshotter\Exception\TimeoutException', 'Page load timeout.'
        );
        $webshot
            ->setUrl('http://httpbin.org/delay/10')
            ->setWidth(1200)
            ->setHeight(800)
            ->setTimeout(5)
            ->saveToPng('delay', $path);
    }

    public function testImagesTimeout()
    {
        $path = realpath(dirname(__FILE__).'/../tests/tmp/');
        $webshot = new Webshot();
        $webshot
            ->setUrl('https://github.com')
            ->setWidth(1200)
            ->setHeight(800)
            ->setTimeout(5)
            ->waitForImages()
            ->setImagesLoadingTimeout(10)
            ->saveToPng('imagestimeout', $path);
    }
}
