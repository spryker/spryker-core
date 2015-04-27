<?php

namespace SprykerFeature\Shared\SearchPage\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\SearchPage\Dependency\PageAttributeInterface;

class PageAttribute extends AbstractTransfer implements PageAttributeInterface
{

    /**
     * @var int
     */
    protected $idSearchPageAttribute = null;

    /**
     * @var string
     */
    protected $keyName = null;

    /**
     * @var bool
     */
    protected $isAttributeActive = null;

    /**
     * @var int
     */
    protected $fkSearchDocumentAttribute = null;

    /**
     * @var int
     */
    protected $fkSearchPageAttributeTemplate = null;

    /**
     * @return int
     */
    public function getIdSearchPageAttribute()
    {
        return $this->idSearchPageAttribute;
    }

    /**
     * @param int $idSearchPageAttribute
     *
     * @return $this
     */
    public function setIdSearchPageAttribute($idSearchPageAttribute)
    {
        $this->idSearchPageAttribute = $idSearchPageAttribute;
        $this->addModifiedProperty('idSearchPageAttribute');

        return $this;
    }

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * @param string $keyName
     *
     * @return $this
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
        $this->addModifiedProperty('keyName');

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsAttributeActive()
    {
        return $this->isAttributeActive;
    }

    /**
     * @param boolean $isAttributeActive
     *
     * @return $this
     */
    public function setIsAttributeActive($isAttributeActive)
    {
        $this->isAttributeActive = $isAttributeActive;
        $this->addModifiedProperty('isAttributeActive');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkSearchDocumentAttribute()
    {
        return $this->fkSearchDocumentAttribute;
    }

    /**
     * @param int $fkSearchDocumentAttribute
     *
     * @return $this
     */
    public function setFkSearchDocumentAttribute($fkSearchDocumentAttribute)
    {
        $this->fkSearchDocumentAttribute = $fkSearchDocumentAttribute;
        $this->addModifiedProperty('fkSearchDocumentAttribute');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkSearchPageAttributeTemplate()
    {
        return $this->fkSearchPageAttributeTemplate;
    }

    /**
     * @param int $fkSearchPageAttributeTemplate
     * @return $this
     */
    public function setFkSearchPageAttributeTemplate($fkSearchPageAttributeTemplate)
    {
        $this->fkSearchPageAttributeTemplate = $fkSearchPageAttributeTemplate;
        $this->addModifiedProperty('fkSearchPageAttributeTemplate');

        return $this;
    }
}
