<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

interface ProductToUrlInterface
{

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $resourceId
     *
     * @throws PropelException
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $resourceId);

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl);


    /**
     * @param int $idAbstractProduct
     * @param int $idLocale
     *
     * @return UrlTransfer
     */
    public function getUrlByIdAbstractProductAndIdLocale($idAbstractProduct, $idLocale);

}
