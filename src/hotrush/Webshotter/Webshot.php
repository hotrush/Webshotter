<?php

namespace hotrush\Webshotter;

use Exception;

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
     * Webshot constructor.
     *
     * @param null $binPath
     * @param null $templatePath
     */
    public function __construct($binPath = null, $templatePath = null)
    {

        if ($binPath === null)
        {
            $this->binPath = realpath(dirname(__FILE__).'/../../../bin/phantomjs');
        }

        if ($templatePath === null)
        {
            $this->templatePath = realpath(dirname(__FILE__).'/../../views/webshotter.php');
        }
        $this->fullPage = false;

        return $this;
    }

    /**
     * Set an url to take a shot
     *
     * @param $url
     * @throws Exception
     * @return $this
     */
    public function setUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL))
        {
            throw new Exception('Invalid url link');
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Set width for viewport and shot
     *
     * @param $width
     * @return $this
     * @throws Exception
     */
    public function setWidth($width)
    {
        if (!is_numeric($width))
        {
            throw new Exception('Invalid width value');
        }

        $this->width = (int) $width;

        return $this;
    }

    /**
     * Set height for viewport and shot
     *
     * @param $height
     * @return $this
     * @throws Exception
     */
    public function setHeight($height)
    {
        if (!is_numeric($height))
        {
            throw new Exception('Invalid height value');
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
            'fullPage' => $this->fullPage
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

        return $fullPath;
    }
}
