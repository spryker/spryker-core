<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStoragePersistenceFactory getFactory()
 */
class MerchantProductOfferStorageEntityManager extends AbstractEntityManager implements MerchantProductOfferStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer $productConcreteProductOffersStorageTransfer
     *
     * @return void
     */
    public function saveProductConcreteProductOffersStorage(ProductConcreteProductOffersStorageTransfer $productConcreteProductOffersStorageTransfer): void
    {
        $productConcreteProductOffersStorageEntity = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku($productConcreteProductOffersStorageTransfer->getConcreteSku())
            ->findOneOrCreate();

        $productConcreteProductOffersStorageEntity = $this->getFactory()
            ->createProductConcreteProductOffersStorageMapper()
            ->mapProductConcreteProductOffersStorageTransferToProductConcreteProductOffersStorageEntity(
                $productConcreteProductOffersStorageTransfer,
                $productConcreteProductOffersStorageEntity
            );

        $productConcreteProductOffersStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return void
     */
    public function saveProductOfferStorage(ProductOfferStorageTransfer $productOfferStorageTransfer): void
    {
        $productOfferStorageEntity = $this->getFactory()
            ->createProductOfferStoragePropelQuery()
            ->filterByProductOfferReference($productOfferStorageTransfer->getProductOfferReference())
            ->findOneOrCreate();

        $productOfferStorageEntity = $this->getFactory()
            ->createProductOfferStorageMapper()
            ->mapProductOfferStorageTransferToProductOfferStorageEntity(
                $productOfferStorageTransfer,
                $productOfferStorageEntity
            );

        $productOfferStorageEntity->save();
    }

    /**
     * @param string[] $concreteSkus
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorage(array $concreteSkus): void
    {
        $productConcreteProductOffersStorageEntities = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku_In($concreteSkus)
            ->find();

        foreach ($productConcreteProductOffersStorageEntities as $productConcreteProductOffersStorageEntity) {
            $productConcreteProductOffersStorageEntity->delete();
        }
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function deleteProductOfferStorage(array $productOfferReferences): void
    {
        $productOfferStorageEntities = $this->getFactory()
            ->createProductOfferStoragePropelQuery()
            ->filterByProductOfferReference_In($productOfferReferences)
            ->find();

        foreach ($productOfferStorageEntities as $productOfferStorageEntity) {
            $productOfferStorageEntity->delete();
        }
    }
}
