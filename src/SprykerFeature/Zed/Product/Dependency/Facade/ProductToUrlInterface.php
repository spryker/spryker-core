<?php

namespace SprykerFeature\Zed\Product\Dependency\Facade;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Shared\Url\Transfer\Url;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

interface ProductToUrlInterface
{
    /**
     * @param string $url
     * @param string $localeName
     * @param string $resourceType
     * @param int $resourceId
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrl($url, $localeName, $resourceType, $resourceId);

    /**
     * @param string $url
     * @param int $fkLocale
     * @param string $resourceType
     * @param int $resourceId
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrlByLocaleFk($url, $fkLocale, $resourceType, $resourceId);

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl);
}
