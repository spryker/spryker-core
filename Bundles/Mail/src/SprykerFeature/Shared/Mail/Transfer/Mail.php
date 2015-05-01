<?php 

namespace SprykerFeature\Shared\Mail\Transfer;

/**
 *
 */
class Mail extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $referenceId = null;

    protected $type = null;

    protected $subType = null;

    protected $priority = null;

    protected $recipientAddress = null;

    protected $recipientFullname = null;

    protected $senderAddress = null;

    protected $senderFullname = null;

    protected $replyToAddress = null;

    protected $subject = null;

    protected $charset = null;

    protected $data = null;

    protected $isOrderMail = null;

    protected $isTestOrder = null;

    protected $isUnique = null;

    protected $yvesUrl = null;

    protected $dateInterval = null;

    protected $staticMediaUrl = null;

    protected $staticAssetsUrl = null;

    protected $attachments = array(
        
    );

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

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->addModifiedProperty('type');
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $subType
     * @return $this
     */
    public function setSubType($subType)
    {
        $this->subType = $subType;
        $this->addModifiedProperty('subType');
        return $this;
    }

    /**
     * @return string
     */
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        $this->addModifiedProperty('priority');
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param string $recipientAddress
     * @return $this
     */
    public function setRecipientAddress($recipientAddress)
    {
        $this->recipientAddress = $recipientAddress;
        $this->addModifiedProperty('recipientAddress');
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientAddress()
    {
        return $this->recipientAddress;
    }

    /**
     * @param string $recipientFullname
     * @return $this
     */
    public function setRecipientFullname($recipientFullname)
    {
        $this->recipientFullname = $recipientFullname;
        $this->addModifiedProperty('recipientFullname');
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientFullname()
    {
        return $this->recipientFullname;
    }

    /**
     * @param string $senderAddress
     * @return $this
     */
    public function setSenderAddress($senderAddress)
    {
        $this->senderAddress = $senderAddress;
        $this->addModifiedProperty('senderAddress');
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderAddress()
    {
        return $this->senderAddress;
    }

    /**
     * @param string $senderFullname
     * @return $this
     */
    public function setSenderFullname($senderFullname)
    {
        $this->senderFullname = $senderFullname;
        $this->addModifiedProperty('senderFullname');
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderFullname()
    {
        return $this->senderFullname;
    }

    /**
     * @param string $replyToAddress
     * @return $this
     */
    public function setReplyToAddress($replyToAddress)
    {
        $this->replyToAddress = $replyToAddress;
        $this->addModifiedProperty('replyToAddress');
        return $this;
    }

    /**
     * @return string
     */
    public function getReplyToAddress()
    {
        return $this->replyToAddress;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        $this->addModifiedProperty('subject');
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $charset
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        $this->addModifiedProperty('charset');
        return $this;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        $this->addModifiedProperty('data');
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @param bool $isOrderMail
     * @return $this
     */
    public function setIsOrderMail($isOrderMail)
    {
        $this->isOrderMail = $isOrderMail;
        $this->addModifiedProperty('isOrderMail');
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsOrderMail()
    {
        return $this->isOrderMail;
    }

    /**
     * @param bool $isTestOrder
     * @return $this
     */
    public function setIsTestOrder($isTestOrder)
    {
        $this->isTestOrder = $isTestOrder;
        $this->addModifiedProperty('isTestOrder');
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsTestOrder()
    {
        return $this->isTestOrder;
    }

    /**
     * @param bool $isUnique
     * @return $this
     */
    public function setIsUnique($isUnique)
    {
        $this->isUnique = $isUnique;
        $this->addModifiedProperty('isUnique');
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsUnique()
    {
        return $this->isUnique;
    }

    /**
     * @param string $yvesUrl
     * @return $this
     */
    public function setYvesUrl($yvesUrl)
    {
        $this->yvesUrl = $yvesUrl;
        $this->addModifiedProperty('yvesUrl');
        return $this;
    }

    /**
     * @return string
     */
    public function getYvesUrl()
    {
        return $this->yvesUrl;
    }

    /**
     * @param string $dateInterval
     * @return $this
     */
    public function setDateInterval($dateInterval)
    {
        $this->dateInterval = $dateInterval;
        $this->addModifiedProperty('dateInterval');
        return $this;
    }

    /**
     * @return string
     */
    public function getDateInterval()
    {
        return $this->dateInterval;
    }

    /**
     * @param string $staticMediaUrl
     * @return $this
     */
    public function setStaticMediaUrl($staticMediaUrl)
    {
        $this->staticMediaUrl = $staticMediaUrl;
        $this->addModifiedProperty('staticMediaUrl');
        return $this;
    }

    /**
     * @return string
     */
    public function getStaticMediaUrl()
    {
        return $this->staticMediaUrl;
    }

    /**
     * @param string $staticAssetsUrl
     * @return $this
     */
    public function setStaticAssetsUrl($staticAssetsUrl)
    {
        $this->staticAssetsUrl = $staticAssetsUrl;
        $this->addModifiedProperty('staticAssetsUrl');
        return $this;
    }

    /**
     * @return string
     */
    public function getStaticAssetsUrl()
    {
        return $this->staticAssetsUrl;
    }

    /**
     * @param array $attachments
     * @return $this
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        $this->addModifiedProperty('attachments');
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param mixed $attachment
     * @return array
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }


}
