<?php

namespace SprykerFeature\Zed\Product\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

interface ProductToUrlInterface
{
    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $resourceId
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $resourceId);

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl);
}
