<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;

class ProductToUrlBridge implements ProductToUrlInterface
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
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $resourceId)
    {
        return $this->urlFacade->createUrl($url, $locale, $resourceType, $resourceId);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrl(UrlTransfer $urlTransfer)
    {
        return $this->urlFacade->saveUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function deleteUrl(UrlTransfer $urlTransfer)
    {
        return $this->urlFacade->deleteUrl($urlTransfer);
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

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url)
    {
        return $this->urlFacade->hasUrl($url);
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
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl)
    {
        $this->urlFacade->touchUrlDeleted($idUrl);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function slugify($value)
    {
        return $this->urlFacade->slugify($value);
    }

}
