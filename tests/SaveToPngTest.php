<?php

use hotrush\Webshotter\Webshot;

class SaveToPngTest extends PHPUnit_Framework_TestCase
{
    public function testImagePngCreating()
    {
        $path = realpath(dirname(__FILE__).'/../tests/tmp/');
        $webshot = new Webshot();
        $png = $webshot
            ->setUrl('https://github.com')
            ->setWidth(1200)
            ->setHeight(800)
            ->saveToPng('github', $path);
        $this->assertFileExists($png);
    }
}
