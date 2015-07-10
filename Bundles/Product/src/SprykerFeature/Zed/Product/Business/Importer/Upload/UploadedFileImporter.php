<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Upload;

/**
 * Class UploadedFileImporter
 */
class UploadedFileImporter
{

    /**
     * @var string
     */
    protected $uploadDestination;

    /**
     * @param string $uploadDestination
     */
    public function __construct($uploadDestination)
    {
        $this->uploadDestination = $uploadDestination;
    }

    /**
     * @param string $uploadedFilename
     *
     * @throws \Zend_File_Transfer_Exception
     *
     * @return \SplFileInfo
     */
    public function receiveUploadedFile($uploadedFilename)
    {
        $httpAdapter = new \Zend_File_Transfer_Adapter_Http();
        $fileInfo = $httpAdapter->getFileInfo();
        $filename = $fileInfo[$uploadedFilename]['name'];
        $pathInfo = pathinfo($filename);
        $destination = rtrim($this->uploadDestination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $newFilename = $this->createFilename($pathInfo);
        $httpAdapter->addFilter('Rename', $destination . $newFilename);
        $httpAdapter->receive();

        return new \SplFileInfo($destination . $newFilename);
    }

    /**
     * @param array $pathInfo
     *
     * @return string
     */
    protected function createFilename(array $pathInfo)
    {
        return sprintf('%s_%s.%s', date('YmdHis'), $pathInfo['filename'], $pathInfo['extension']);
    }

}
