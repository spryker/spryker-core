<?php 

namespace SprykerFeature\Shared\Sales\Transfer;

/**
 *
 */
class Document extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $header = null;

    protected $fileName = null;

    protected $filePath = null;

    /**
     * @param string $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;
        $this->addModifiedProperty('header');
        return $this;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        $this->addModifiedProperty('fileName');
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        $this->addModifiedProperty('filePath');
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }


}
