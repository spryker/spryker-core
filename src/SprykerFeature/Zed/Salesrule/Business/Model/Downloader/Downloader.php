<?php

namespace SprykerFeature\Zed\Salesrule\Business\Downloader;

class Downloader
{

    /**
     * @var array
     */
    protected $header = [];

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @param $filePath
     * @throws DownloaderException
     */
    public function __construct($filePath)
    {
        if (!is_file($filePath)) {
            throw new DownloaderException('Given file path "' . $filePath . '" is not a valid path!');
        }
        $this->filePath = dirname($filePath);
        $this->fileName = str_replace($this->filePath . DIRECTORY_SEPARATOR, '', $filePath);
        $this->setDefaultHeader();
    }

    protected function setDefaultHeader()
    {
        $this->addHeader('Content-Description: File Transfer');
        $this->addHeader('Content-Disposition: attachment; filename="' . $this->fileName . '"');
        $this->addHeader('Content-Length: ' . filesize($this->filePath . DIRECTORY_SEPARATOR . $this->fileName));
    }

    /**
     * @param $header
     * @return $this
     */
    public function addHeader($header)
    {
        $this->header[] = $header;

        return $this;
    }

    public function download()
    {
        $this->header();
        $this->readFile();
    }

    /**
     * @codeCoverageIgnore
     */
    protected function header()
    {
        foreach ($this->header as $header) {
            header($header);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    protected function readFile()
    {
        readfile($this->filePath . DIRECTORY_SEPARATOR . $this->fileName);
    }
}
