<?php

namespace hotrush\Webshotter;

use hotrush\Webshotter\Exception\TimeoutException;
use hotrush\Webshotter\Exception\InvalidDataException;

class Webshot
{
    /**
     * Site's url
     *
     * @var string
     */
    private $url;

    /**
     * Viewport width
     *
     * @var int
     */
    private $width;

    /**
     * Viewport height
     *
     * @var int
     */
    private $height;

    /**
     * Full page shot flag
     *
     * @var bool
     */
    private $fullPage;

    /**
     * Path to PhantomJS executable
     *
     * @var string
     */
    private $binPath;

    /**
     * Path to template
     *
     * @var string
     */
    private $templatePath;

    /**
     * Page load timeout in sec
     *
     * @var int
     */
    private $timeout;

    /**
     * Webshot constructor.
     *
     * @param null $binPath
     * @param null $templatePath
     */
    public function __construct($binPath = null, $templatePath = null)
    {

    	$this->binPath = $binPath;
        if ($binPath === null)
        {
            $this->binPath = realpath(dirname(__FILE__).'/../../../bin/phantomjs');
        }

        if ($templatePath === null)
        {
            $this->templatePath = realpath(dirname(__FILE__).'/../../views/webshotter.php');
        }
        $this->fullPage = false;
        $this->timeout = 30;

        return $this;
    }

    /**
     * Set an url to take a shot
     *
     * @param $url
     * @throws InvalidDataException
     * @return $this
     */
    public function setUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL))
        {
            throw new InvalidDataException('Invalid url link');
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Set width for viewport and shot
     *
     * @param $width
     * @return $this
     * @throws InvalidDataException
     */
    public function setWidth($width)
    {
        if (!is_numeric($width))
        {
            throw new InvalidDataException('Invalid width value');
        }

        $this->width = (int) $width;

        return $this;
    }

    /**
     * Set height for viewport and shot
     *
     * @param $height
     * @return $this
     * @throws InvalidDataException
     */
    public function setHeight($height)
    {
        if (!is_numeric($height))
        {
            throw new InvalidDataException('Invalid height value');
        }

        $this->height = (int) $height;

        return $this;
    }

    /**
     * Set to true to get full page shot
     * or to false to get clipped to viewport width/height image
     *
     * @param $fullPage
     * @return $this
     */
    public function setFullPage($fullPage = true)
    {
        $this->fullPage = (bool) $fullPage;

        return $this;
    }

    /**
     * Set timeout value in sec
     *
     * @param $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int) $timeout;

        return $this;
    }

    /**
     * Render PhantomJS script template
     *
     * @param $filePath
     * @return string
     */
    private function renderTemplate($filePath)
    {
        return $this->render(array(
            'url' => $this->url,
            'width' => $this->width,
            'height' => $this->height,
            'fullPage' => $this->fullPage,
            'timeout' => $this->timeout * 1000, // convert into milliseconds
        ), $filePath);
    }

    /**
     * @param array $vars
     * @param $path
     * @return string
     */
    private function render(array $vars, $path)
    {
        extract($vars);

        ob_start();

        require $this->templatePath;

        $template = ob_get_contents();

        ob_end_clean();

        return $template;
    }

    /**
     * Save shot to png image
     *
     * @param $fileName
     * @param $filePath
     * @return string
     */
    public function saveToPng($fileName, $filePath)
    {
        return $this->save($fileName, $filePath, '.png');
    }

    /**
     * Save shot to jpg image
     *
     * @param $fileName
     * @param $filePath
     * @return string
     */
    public function saveToJpg($fileName, $filePath)
    {
        return $this->save($fileName, $filePath, '.jpg');
    }

    /**
     * Save shot to pdf doc
     *
     * @param $fileName
     * @param $filePath
     * @return string
     */
    public function saveToPdf($fileName, $filePath)
    {
        return $this->save($fileName, $filePath, '.pdf');
    }

    /**
     * Save shot
     *
     * @param $fileName
     * @param $filePath
     * @param $extension
     * @throws TimeoutException
     * @return string
     */
    private function save($fileName, $filePath, $extension)
    {
        $fullPath = rtrim($filePath, '/').DIRECTORY_SEPARATOR.$fileName.$extension;

        $template = $this->renderTemplate($fullPath);

        $tempExecutable = tmpfile();
        fwrite($tempExecutable, $template);
        $tempFileName = stream_get_meta_data($tempExecutable)['uri'];
        $cmd = escapeshellcmd("{$this->binPath} --ssl-protocol=any --ignore-ssl-errors=true ".$tempFileName);
        shell_exec($cmd);
        fclose($tempExecutable);

        if (!file_exists($fullPath))
        {
            throw new TimeoutException('Page load timeout.');
        }

        return $fullPath;
    }
}
