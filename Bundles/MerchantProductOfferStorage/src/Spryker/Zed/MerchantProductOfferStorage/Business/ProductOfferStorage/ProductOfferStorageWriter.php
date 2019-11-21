<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;

class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
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

        //As far as I know we don't have to use facade methods in storage module, don't we?
        //Instead of this you have to get all the needed data + store data
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
                //Should be removed. You can get all the data by one query.
                $productOfferStores = SpyProductOfferStoreQuery::create()
                ->findByFkProductOffer($productOfferTransfer->getIdProductOffer());

            /** @var SpyProductOfferStore $storeTransfer */
            foreach ($productOfferStores as $storeTransfer) {
                $productOfferStorageEntity = SpyProductOfferStorageQuery::create()
                    ->filterByProductOfferReference($productOfferTransfer->getProductOfferReference())
                    ->filterByStore($storeTransfer->getSpyStore()->getName())
                    ->findOneOrCreate();
                $productOfferStorageEntity->setData($this->createProductOfferStorageTransfer($productOfferTransfer)->modifiedToArray());
                $productOfferStorageEntity->setStore($storeTransfer->getSpyStore()->getName());

                $productOfferStorageEntity->save();
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

        return $productOfferStorageTransfer;
    }
}
