[![Build Status](https://img.shields.io/travis/hotrush/webshotter/master.svg?style=flat-square)](https://travis-ci.org/hotrush/Webshotter)
[![Latest Version](https://img.shields.io/github/release/hotrush/webshotter.svg?style=flat-square)](https://github.com/hotrush/webshotter/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/hotrush/webshotter.svg?style=flat-square)](https://packagist.org/packages/hotrush/webshotter)

# Webshotter

Take website's screenshots with PHP/PhantomJS and save them to PNG, JPG or PDF

## Installation

```
composer require hotrush/webshotter
```

## Usage

```
$webshot = new hotrush\Webshotter\Webshot();
$jpg = $webshot
    ->setUrl('https://github.com')
    ->setWidth(1200)
    ->setHeight(800)
    ->setFullPage(true) // set to true to get full page screenshot (width/height will be used for viewport only) 
    ->saveToPng('github', $path);
```

You can use ```saveToJpg```, ```saveToPng``` or ```saveToPdf``` methods. This methods requires 2 parameters: file name (without extension) and target directory to save file. They all returns full path to saved file

If you want to use you own PhantomJs executable - you can specify path to it via constructor.

```
new hotrush\Webshotter\Webshot('/path/to/phantomjs');
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.