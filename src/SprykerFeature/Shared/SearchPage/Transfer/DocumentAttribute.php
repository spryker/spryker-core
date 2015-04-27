<?php

namespace SprykerFeature\Shared\SearchPage\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\SearchPage\Dependency\DocumentAttributeInterface;

class DocumentAttribute extends AbstractTransfer implements DocumentAttributeInterface
{
    /**
     * @var int
     */
    protected $idSearchDocumentAttribute;

    /**
     * @var string
     */
    protected $attributeName;

    /**
     * @var string
     */
    protected $documentType;

    /**
     * @return int
     */
    public function getIdSearchDocumentAttribute()
    {
        return $this->idSearchDocumentAttribute;
    }

    /**
     * @param int $idSearchDocumentAttribute
     *
     * @return $this
     */
    public function setIdSearchDocumentAttribute($idSearchDocumentAttribute)
    {
        $this->idSearchDocumentAttribute = $idSearchDocumentAttribute;
        $this->addModifiedProperty('idSearchDocumentAttribute');

        return $this;
    }

    /**
     * @return string
     */
    public function getAttributeName()
    {
        return $this->attributeName;
    }

    /**
     * @param string $attributeName
     *
     * @return $this
     */
    public function setAttributeName($attributeName)
    {
        $this->attributeName = $attributeName;
        $this->addModifiedProperty('attributeName');

        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * @param string $documentType
     *
     * @return $this
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
        $this->addModifiedProperty('documentType');

        return $this;
    }
}
