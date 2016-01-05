<?php

use hotrush\Webshotter\Webshot;

class SaveToJpgTest extends PHPUnit_Framework_TestCase
{
    public function testImageJpgCreating()
    {
        $path = realpath(dirname(__FILE__).'/../tests/tmp/');
        $webshot = new Webshot();
        $jpg = $webshot
            ->setUrl('https://github.com')
            ->setWidth(1200)
            ->setHeight(800)
            ->saveToJpg('github', $path);
        $this->assertFileExists($jpg);
    }
}
