<?php

namespace SprykerFeature\Shared\SearchPage\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\SearchPage\Dependency\PageElementInterface;

class PageElement extends AbstractTransfer implements PageElementInterface
{

    /**
     * @var int
     */
    protected $idSearchPageElement = null;

    /**
     * @var string
     */
    protected $elementKey = null;

    /**
     * @var bool
     */
    protected $isElementActive = null;

    /**
     * @var int
     */
    protected $fkSearchDocumentAttribute = null;

    /**
     * @var int
     */
    protected $fkSearchPageElementTemplate = null;

    /**
     * @return int
     */
    public function getIdSearchPageElement()
    {
        return $this->idSearchPageElement;
    }

    /**
     * @param int $idSearchPageElement
     *
     * @return $this
     */
    public function setIdSearchPageElement($idSearchPageElement)
    {
        $this->idSearchPageElement = $idSearchPageElement;
        $this->addModifiedProperty('idSearchPageElement');

        return $this;
    }

    /**
     * @return string
     */
    public function getElementKey()
    {
        return $this->elementKey;
    }

    /**
     * @param string $elementKey
     *
     * @return $this
     */
    public function setElementKey($elementKey)
    {
        $this->elementKey = $elementKey;
        $this->addModifiedProperty('elementKey');

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsElementActive()
    {
        return $this->isElementActive;
    }

    /**
     * @param boolean $isElementActive
     *
     * @return $this
     */
    public function setIsElementActive($isElementActive)
    {
        $this->isElementActive = $isElementActive;
        $this->addModifiedProperty('isElementActive');

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
        $this->addModifiedProperty('fkSearchDocumentElement');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkSearchPageElementTemplate()
    {
        return $this->fkSearchPageElementTemplate;
    }

    /**
     * @param int $fkSearchPageElementTemplate
     * @return $this
     */
    public function setFkSearchPageElementTemplate($fkSearchPageElementTemplate)
    {
        $this->fkSearchPageElementTemplate = $fkSearchPageElementTemplate;
        $this->addModifiedProperty('fkSearchPageElementTemplate');

        return $this;
    }
}
