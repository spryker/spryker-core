<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductManagementToUrlBridge implements ProductManagementToUrlInterface
{

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Url\Business\UrlFacadeInterface $urlFacade
     */
    public function __construct($urlFacade)
    {
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $resourceType
     * @param int $resourceId
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $resourceId)
    {
        return $this->urlFacade->createUrl($url, $locale, $resourceType, $resourceId);
    }

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl)
    {
        $this->urlFacade->touchUrlActive($idUrl);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
    {
        return $this->urlFacade->getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);
    }

}
