<?php 

namespace SprykerFeature\Shared\Mail\Transfer;

/**
 *
 */
class Template extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idMailTemplate = null;

    protected $name = null;

    protected $subject = null;

    protected $sender = null;

    protected $senderName = null;

    protected $text = null;

    protected $html = null;

    protected $wrapper = 0;

    protected $deleted = null;

    protected $version = null;

    protected $dateInterval = null;

    protected $versionCreatedAt = null;

    protected $versionCreatedBy = null;

    /**
     * @param int $idMailTemplate
     * @return $this
     */
    public function setIdMailTemplate($idMailTemplate)
    {
        $this->idMailTemplate = $idMailTemplate;
        $this->addModifiedProperty('idMailTemplate');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdMailTemplate()
    {
        return $this->idMailTemplate;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param int $sender
     * @return $this
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        $this->addModifiedProperty('sender');
        return $this;
    }

    /**
     * @return int
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param int $senderName
     * @return $this
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
        $this->addModifiedProperty('senderName');
        return $this;
    }

    /**
     * @return int
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        $this->addModifiedProperty('text');
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $html
     * @return $this
     */
    public function setHtml($html)
    {
        $this->html = $html;
        $this->addModifiedProperty('html');
        return $this;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param int $wrapper
     * @return $this
     */
    public function setWrapper($wrapper)
    {
        $this->wrapper = $wrapper;
        $this->addModifiedProperty('wrapper');
        return $this;
    }

    /**
     * @return int
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * @param bool $deleted
     * @return $this
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        $this->addModifiedProperty('deleted');
        return $this;
    }

    /**
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param int $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        $this->addModifiedProperty('version');
        return $this;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
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
     * @param string $versionCreatedAt
     * @return $this
     */
    public function setVersionCreatedAt($versionCreatedAt)
    {
        $this->versionCreatedAt = $versionCreatedAt;
        $this->addModifiedProperty('versionCreatedAt');
        return $this;
    }

    /**
     * @return string
     */
    public function getVersionCreatedAt()
    {
        return $this->versionCreatedAt;
    }

    /**
     * @param string $versionCreatedBy
     * @return $this
     */
    public function setVersionCreatedBy($versionCreatedBy)
    {
        $this->versionCreatedBy = $versionCreatedBy;
        $this->addModifiedProperty('versionCreatedBy');
        return $this;
    }

    /**
     * @return string
     */
    public function getVersionCreatedBy()
    {
        return $this->versionCreatedBy;
    }


}
