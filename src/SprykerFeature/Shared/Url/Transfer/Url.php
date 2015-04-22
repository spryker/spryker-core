<?php

namespace SprykerFeature\Shared\Url\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Url extends AbstractTransfer
{
    /**
     * @var int
     */
    protected $idUrl;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $fkLocale;

    /**
     * @var string
     */
    protected $itemType;

    /**
     * @var int
     */
    protected $itemId;


    //TODO these properties do not belong here
    /**
     * @var int
     */
    protected $fkProductId;

    /**
     * @var int
     */
    protected $fkCategoryId;

    /**
     * @var int
     */
    protected $fkPageId;

    /**
     * @var int
     */
    protected $fkRedirectId;

    /**
     * @var string
     */
    protected $resourceType;

    /**
     * @var int
     */
    protected $resourceId;

    /**
     * @return int
     */
    public function getIdUrl()
    {
        return $this->idUrl;
    }

    /**
     * @param int $idUrl
     *
     * @return $this
     */
    public function setIdUrl($idUrl)
    {
        $this->addModifiedProperty('idUrl');
        $this->idUrl = $idUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->addModifiedProperty('url');
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getFkLocale()
    {
        return $this->fkLocale;
    }

    /**
     * @param int $fkLocale
     *
     * @return $this
     */
    public function setFkLocale($fkLocale)
    {
        $this->addModifiedProperty('fkLocale');
        $this->fkLocale = $fkLocale;

        return $this;
    }

    /**
     * @param string $resourceType
     * @param int $resourceId
     *
     * @return $this
     */
    public function setResource($resourceType, $resourceId)
    {
        $this->addModifiedProperty('resource');
        $this->resourceType = $resourceType;
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * @return int
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @return int
     */
    public function getFkProductId()
    {
        return $this->fkProductId;
    }

    /**
     * @param int $fkProductId
     *
     * @return $this
     */
    public function setFkProductId($fkProductId)
    {
        $this->addModifiedProperty('fkProductId');
        $this->fkProductId = $fkProductId;

        return $this;
    }

    /**
     * @return int
     */
    public function getFkCategoryId()
    {
        return $this->fkCategoryId;
    }

    /**
     * @param int $fkCategoryId
     *
     * @return $this
     */
    public function setFkCategoryId($fkCategoryId)
    {
        $this->addModifiedProperty('fkCategoryId');
        $this->fkCategoryId = $fkCategoryId;

        return $this;
    }

    /**
     * @return int
     */
    public function getFkPageId()
    {
        return $this->fkPageId;
    }

    /**
     * @param int $fkPageId
     *
     * @return $this
     */
    public function setFkPageId($fkPageId)
    {
        $this->addModifiedProperty('fkPageId');
        $this->fkPageId = $fkPageId;

        return $this;
    }

    /**
     * @return int
     */
    public function getFkRedirectId()
    {
        return $this->fkRedirectId;
    }

    /**
     * @param int $fkRedirectId
     *
     * @return $this
     */
    public function setFkRedirectId($fkRedirectId)
    {
        $this->addModifiedProperty('fkRedirectId');
        $this->fkRedirectId = $fkRedirectId;

        return $this;
    }
}
