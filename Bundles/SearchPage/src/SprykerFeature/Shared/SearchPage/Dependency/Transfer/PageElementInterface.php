<?php

namespace SprykerFeature\Shared\SearchPage\Dependency\Transfer;

interface PageElementInterface
{
    /**
     * @return int
     */
    public function getIdSearchPageElement();

    /**
     * @param int $idSearchPageAttribute
     *
     * @return $this
     */
    public function setIdSearchPageElement($idSearchPageAttribute);

    /**
     * @return string
     */
    public function getElementKey();

    /**
     * @param string $elementKey
     *
     * @return $this
     */
    public function setElementKey($elementKey);

    /**
     * @return boolean
     */
    public function getIsElementActive();

    /**
     * @param boolean $isElementActive
     *
     * @return $this
     */
    public function setIsElementActive($isElementActive);

    /**
     * @return int
     */
    public function getFkSearchDocumentAttribute();

    /**
     * @param int $fkSearchDocumentAttribute
     *
     * @return $this
     */
    public function setFkSearchDocumentAttribute($fkSearchDocumentAttribute);

    /**
     * @return int
     */
    public function getFkSearchPageElementTemplate();

    /**
     * @param int $fkSearchPageElementTemplate
     * @return $this
     */
    public function setFkSearchPageElementTemplate($fkSearchPageElementTemplate);

    /**
     * @param bool $includeNullValues
     * @param bool $recursive
     * @param bool $formatToUnderscore
     *
     * @return array
     */
    public function toArray($includeNullValues = true, $recursive = true, $formatToUnderscore = true);
}
