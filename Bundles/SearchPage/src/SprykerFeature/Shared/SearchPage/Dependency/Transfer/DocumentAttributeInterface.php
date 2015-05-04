<?php

namespace SprykerFeature\Shared\SearchPage\Dependency\Transfer;

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
    public function getAttributeType();

    /**
     * @param string $documentType
     *
     * @return $this
     */
    public function setAttributeType($documentType);

    /**
     * @param bool $includeNullValues
     * @param bool $recursive
     * @param bool $formatToUnderscore
     *
     * @return array
     */
    public function toArray($includeNullValues = true, $recursive = true, $formatToUnderscore = true);
}
