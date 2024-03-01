<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data\Url;

use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetRepositoryInterface;

class ProductSetUrlUpdater implements ProductSetUrlUpdaterInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetRepositoryInterface
     */
    protected ProductSetRepositoryInterface $repository;

    /**
     * @var \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface
     */
    protected ProductSetToUrlInterface $urlFacade;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlCreatorInterface
     */
    protected ProductSetUrlCreatorInterface $productSetUrlCreator;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetRepositoryInterface $repository
     * @param \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface $urlFacade
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlCreatorInterface $productSetUrlCreator
     */
    public function __construct(
        ProductSetRepositoryInterface $repository,
        ProductSetToUrlInterface $urlFacade,
        ProductSetUrlCreatorInterface $productSetUrlCreator
    ) {
        $this->repository = $repository;
        $this->urlFacade = $urlFacade;
        $this->productSetUrlCreator = $productSetUrlCreator;
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
        $urlTransfer = $this->repository->findProductSetUrl($idProductSet, $idLocale);
        if ($urlTransfer) {
            $urlTransfer->setUrl($localizedProductSetTransfer->getUrl());
            $this->urlFacade->updateUrl($urlTransfer);

            return $localizedProductSetTransfer;
        }

        return $this->productSetUrlCreator->createUrl($localizedProductSetTransfer, $idProductSet);
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
}
