<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Spryker\Zed\MerchantProductOfferStorage\Business\ProductOffer\ProductOfferAvailabilityCheckerInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;

class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Business\ProductOffer\ProductOfferAvailabilityCheckerInterface
     */
    protected $productOfferAvailabilityChecker;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Business\ProductOffer\ProductOfferAvailabilityCheckerInterface $productOfferAvailabilityChecker
     */
    public function __construct(
        MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        ProductOfferAvailabilityCheckerInterface $productOfferAvailabilityChecker
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->productOfferAvailabilityChecker = $productOfferAvailabilityChecker;
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
            $productOfferStorageEntity = SpyProductOfferStorageQuery::create()
                ->filterByProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->findOneOrCreate();
            $productOfferStorageTransfer = $this->createProductOfferStorageTransfer($productOfferTransfer);

            $productOfferStorageEntity->setData($productOfferStorageTransfer->modifiedToArray());

            if ($this->productOfferAvailabilityChecker->isProductOfferAvailable($productOfferTransfer)) {//TODO: pass store transfer
                $productOfferStorageEntity->save();
            } elseif (!$productOfferStorageEntity->isNew()) {
                $productOfferStorageEntity->delete();
            }
        }
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function unpublish(array $productOfferReferences): void
    {
        $productOfferStorageEntities = SpyProductOfferStorageQuery::create()->filterByProductOfferReference_In($productOfferReferences)->find();

        foreach ($productOfferStorageEntities as $productOfferStorageEntity) {
            $productOfferStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    protected function createProductOfferStorageTransfer(ProductOfferTransfer $productOfferTransfer): ProductOfferStorageTransfer
    {
        $productOfferStorageTransfer = new ProductOfferStorageTransfer();

        $productOfferStorageTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());
        $productOfferStorageTransfer->setIdMerchant($productOfferTransfer->getFkMerchant());
        $productOfferStorageTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $productOfferStorageTransfer->setMerchantSku($productOfferTransfer->getMerchantSku());

        return $productOfferStorageTransfer;
    }
}
