<?php

use hotrush\Webshotter\Webshot;

class PropertiesTest extends PHPUnit_Framework_TestCase
{
    public function testCreating()
    {
        $webshot = new Webshot();
        $this->assertInstanceOf('hotrush\Webshotter\Webshot', $webshot);
        $this->assertFalse(PHPUnit_Framework_Assert::readAttribute($webshot, 'fullPage'));
        $path = realpath(dirname(__FILE__).'/../');
        $this->assertEquals($path.'/bin/phantomjs', PHPUnit_Framework_Assert::readAttribute($webshot, 'binPath'));
        $this->assertEquals($path.'/src/views/webshotter.php', PHPUnit_Framework_Assert::readAttribute($webshot, 'templatePath'));
    }

    public function testWidthProperty()
    {
        $webshot = new Webshot();
        $this->setExpectedException(
            'hotrush\Webshotter\Exception\InvalidDataException', 'Invalid width value'
        );
        $webshot->setWidth('foo');
        $webshot->setWidth(1000);
        $this->assertEquals(1000, PHPUnit_Framework_Assert::readAttribute($webshot, 'width'));
    }

    public function testHeightProperty()
    {
        $webshot = new Webshot();
        $this->setExpectedException(
            'hotrush\Webshotter\Exception\InvalidDataException', 'Invalid height value'
        );
        $webshot->setHeight('foo');
        $webshot->setHeight(1000);
        $this->assertEquals(1000, PHPUnit_Framework_Assert::readAttribute($webshot, 'height'));
    }

    public function testUrlProperty()
    {
        $webshot = new Webshot();
        $this->setExpectedException(
            'hotrush\Webshotter\Exception\InvalidDataException', 'Invalid url link'
        );
        $webshot->setUrl('foo-bar-url');
        $webshot->setUrl('https://github.com');
        $this->assertEquals('https://github.com', PHPUnit_Framework_Assert::readAttribute($webshot, 'url'));
    }

    public function testFullPageProperty()
    {
        $webshot = new Webshot();
        $this->assertFalse(PHPUnit_Framework_Assert::readAttribute($webshot, 'fullPage'));
        $webshot->setFullPage(true);
        $this->assertTrue(PHPUnit_Framework_Assert::readAttribute($webshot, 'fullPage'));
    }

    public function testTimeoutProperty()
    {
        $webshot = new Webshot();
        $this->assertEquals(30, PHPUnit_Framework_Assert::readAttribute($webshot, 'timeout'));
        $webshot->setTimeout(10);
        $this->assertEquals(10, PHPUnit_Framework_Assert::readAttribute($webshot, 'timeout'));
    }

    public function testTemplateRendering()
    {
        $webshot = new Webshot();
        $webshot
            ->setUrl('https://github.com')
            ->setWidth(1200)
            ->setHeight(800);
        $mock = file_get_contents(realpath(dirname(__FILE__).'/../tests/data/template.php'));
        $reflection = new ReflectionClass('hotrush\Webshotter\Webshot');
        $method = $reflection->getMethod('renderTemplate');
        $method->setAccessible(true);
        $this->assertEquals($mock, $method->invokeArgs($webshot, array('tests/tmp/github.png')));
    }
}
