<?php

namespace SprykerFeature\Shared\SearchPage\Dependency;

interface PageAttributeInterface
{
    /**
     * @return int
     */
    public function getIdSearchPageAttribute();

    /**
     * @param int $idSearchPageAttribute
     *
     * @return $this
     */
    public function setIdSearchPageAttribute($idSearchPageAttribute);

    /**
     * @return string
     */
    public function getKeyName();

    /**
     * @param string $keyName
     *
     * @return $this
     */
    public function setKeyName($keyName);

    /**
     * @return boolean
     */
    public function isIsAttributeActive();

    /**
     * @param boolean $isAttributeActive
     *
     * @return $this
     */
    public function setIsAttributeActive($isAttributeActive);

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
    public function getFkSearchPageAttributeTemplate();

    /**
     * @param int $fkSearchPageAttributeTemplate
     * @return $this
     */
    public function setFkSearchPageAttributeTemplate($fkSearchPageAttributeTemplate);
}