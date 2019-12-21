<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStoragePersistenceFactory getFactory()
 */
class MerchantProductOfferStorageEntityManager extends AbstractEntityManager implements MerchantProductOfferStorageEntityManagerInterface
{
    /**
     * @param string $concreteSku
     * @param array $data
     * @param string $storeName
     *
     * @return void
     */
    public function saveProductConcreteProductOffersStorage(string $concreteSku, array $data, string $storeName): void
    {
        $productConcreteProductOffersStorageEntity = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku($concreteSku)
            ->filterByStore($storeName)
            ->findOneOrCreate();

        $productConcreteProductOffersStorageEntity->setData($data);
        $productConcreteProductOffersStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    public function saveProductOfferStorage(ProductOfferTransfer $productOfferTransfer): void
    {
        foreach ($productOfferTransfer->getStores() as $storeTransfer) {
            $productOfferStorageEntity = $this->getFactory()
                ->createProductOfferStoragePropelQuery()
                ->filterByStore($storeTransfer->getName())
                ->filterByProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->findOneOrCreate();

            $productOfferStorageTransfer = $this->getFactory()
                ->createProductOfferStorageMapper()
                ->mapProductOfferTransferToProductOfferStorageTransfer($productOfferTransfer, (new ProductOfferStorageTransfer()));

            $productOfferStorageEntity->setData($productOfferStorageTransfer->toArray());
            $productOfferStorageEntity->save();
        }
    }

    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorageEntitiesByProductSkus(array $productSkus): void
    {
        $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku_In($productSkus)
            ->find()
            ->delete();
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function deleteProductOfferStorageEntitiesByProductOfferReferences(array $productOfferReferences): void
    {
        $this->getFactory()
            ->createProductOfferStoragePropelQuery()
            ->filterByProductOfferReference_In($productOfferReferences)
            ->find()
            ->delete();
    }

    /**
     * @param string[] $productSkus
     * @param string $storeName
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorageByProductSkusAndStore(array $productSkus, string $storeName): void
    {
        $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku_In($productSkus)
            ->filterByStore($storeName)
            ->find()
            ->delete();
    }

    /**
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return void
     */
    public function deleteProductOfferStorageEntitiesByProductOfferReferencesAndStore(
        array $productOfferReferences,
        string $storeName
    ): void {
        $this->getFactory()
            ->createProductOfferStoragePropelQuery()
            ->filterByProductOfferReference_In($productOfferReferences)
            ->filterByStore($storeName)
            ->find()
            ->delete();
    }
}
