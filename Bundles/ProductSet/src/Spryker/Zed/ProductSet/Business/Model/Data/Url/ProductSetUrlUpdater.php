<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data\Url;

use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface;

class ProductSetUrlUpdater implements ProductSetUrlUpdaterInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlReaderInterface
     */
    protected $productSetUrlReader;

    /**
     * @var \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlReaderInterface $productSetUrlReader
     * @param \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface $urlFacade
     */
    public function __construct(ProductSetUrlReaderInterface $productSetUrlReader, ProductSetToUrlInterface $urlFacade)
    {
        $this->productSetUrlReader = $productSetUrlReader;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    public function updateUrl(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet)
    {
        $this->assertProductSetForCreateUrl($localizedProductSetTransfer);

        $idLocale = $localizedProductSetTransfer->getLocale()->getIdLocale();

        $urlEntity = $this->productSetUrlReader->getProductSetUrlEntity($idProductSet, $idLocale);

        $urlTransfer = $this->createUrlTransfer($urlEntity, $localizedProductSetTransfer);

        $this->urlFacade->updateUrl($urlTransfer);

        return $localizedProductSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     *
     * @return void
     */
    protected function assertProductSetForCreateUrl(LocalizedProductSetTransfer $localizedProductSetTransfer)
    {
        $localizedProductSetTransfer
            ->requireUrl()
            ->requireLocale();

        $localizedProductSetTransfer->getLocale()->requireIdLocale();
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(SpyUrl $urlEntity, LocalizedProductSetTransfer $localizedProductSetTransfer)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->fromArray($urlEntity->toArray(), true);
        $urlTransfer->setUrl($localizedProductSetTransfer->getUrl());

        return $urlTransfer;
    }
}
