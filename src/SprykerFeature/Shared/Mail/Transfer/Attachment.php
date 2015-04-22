<?php 

namespace SprykerFeature\Shared\Mail\Transfer;

/**
 *
 */
class Attachment extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $fileName = null;

    protected $attachmentUrl = null;

    protected $referenceId = null;

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
     * @param string $attachmentUrl
     * @return $this
     */
    public function setAttachmentUrl($attachmentUrl)
    {
        $this->attachmentUrl = $attachmentUrl;
        $this->addModifiedProperty('attachmentUrl');
        return $this;
    }

    /**
     * @return string
     */
    public function getAttachmentUrl()
    {
        return $this->attachmentUrl;
    }

    /**
     * @param string $referenceId
     * @return $this
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;
        $this->addModifiedProperty('referenceId');
        return $this;
    }

    /**
     * @return string
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }


}
