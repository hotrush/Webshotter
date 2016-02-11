<?php

use hotrush\Webshotter\Webshot;

class SaveToPdfTest extends PHPUnit_Framework_TestCase
{
    public function testImagePdfCreating()
    {
        $path = realpath(dirname(__FILE__).'/../tests/tmp/');
        $webshot = new Webshot();
        $pdf = $webshot
            ->setUrl('https://github.com')
            ->setWidth(1200)
            ->setHeight(800)
            ->setTimeout(5)
            ->saveToPdf('github', $path);
        $this->assertFileExists($pdf);
    }
}
