<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferViewTransfer;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface
     */
    protected $productOfferStorageEntityManager;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
     */
    public function __construct(
        MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->productOfferStorageEntityManager = $productOfferStorageEntityManager;
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function publish(array $productOfferReferences): void
    {
        $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setProductOfferReferences($productOfferReferences);
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferStorageTransfer = new ProductOfferStorageTransfer();
            $productOfferStorageTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());
            $productOfferStorageTransfer->setData($this->createProductOfferViewTransfer($productOfferTransfer)->modifiedToArray());

            $this->productOfferStorageEntityManager->saveProductOfferStorage($productOfferStorageTransfer);
        }
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function unpublish(array $productOfferReferences): void
    {
        $this->productOfferStorageEntityManager->deleteProductOfferStorage($productOfferReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferViewTransfer
     */
    protected function createProductOfferViewTransfer(ProductOfferTransfer $productOfferTransfer): ProductOfferViewTransfer
    {
        $productOfferViewTransfer = new ProductOfferViewTransfer();

        $productOfferViewTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());
        $productOfferViewTransfer->setIdMerchant($productOfferTransfer->getFkMerchant());
        $productOfferViewTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        return $productOfferViewTransfer;
    }
}
