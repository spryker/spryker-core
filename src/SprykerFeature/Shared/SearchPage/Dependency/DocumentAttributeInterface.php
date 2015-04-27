<?php

namespace SprykerFeature\Shared\SearchPage\Dependency;

interface DocumentAttributeInterface
{
    /**
     * @return int
     */
    public function getIdSearchDocumentAttribute();

    /**
     * @param int $idSearchDocumentAttribute
     *
     * @return $this
     */
    public function setIdSearchDocumentAttribute($idSearchDocumentAttribute);

    /**
     * @return string
     */
    public function getAttributeName();

    /**
     * @param string $attributeName
     *
     * @return $this
     */
    public function setAttributeName($attributeName);

    /**
     * @return string
     */
    public function getDocumentType();

    /**
     * @param string $documentType
     *
     * @return $this
     */
    public function setDocumentType($documentType);
}