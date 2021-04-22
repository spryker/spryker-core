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
    public function saveProductConcreteProductOffers(string $concreteSku, array $data, string $storeName): void
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
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorageEntitiesByProductSkus(
        array $productSkus,
        ?string $storeName = null
    ): void {
        $query = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku_In($productSkus);

        if ($storeName) {
            $query->filterByStore($storeName);
        }

        $query->find()->delete();
    }

    /**
     * @param string[] $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferStorageEntitiesByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void
    {
        $query = $this->getFactory()
            ->createProductOfferStoragePropelQuery()
            ->filterByProductOfferReference_In($productOfferReferences);

        if ($storeName) {
            $query->filterByStore($storeName);
        }

        $query->find()->delete();
    }
}
