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
     *
     * @return void
     */
    public function saveProductConcreteProductOffersStorage(string $concreteSku, array $data): void
    {
        $productConcreteProductOffersStorageEntity = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku($concreteSku)
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
        $productOfferStorageEntity = $this->getFactory()
            ->createProductOfferStoragePropelQuery()
            ->filterByProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->findOneOrCreate();

        $productOfferStorageTransfer = $this->getFactory()
            ->createProductOfferStorageMapper()
            ->mapProductOfferTransferToProductOfferStorageTransfer($productOfferTransfer, (new ProductOfferStorageTransfer()));

        $productOfferStorageEntity->setData($productOfferStorageTransfer->modifiedToArray());
        $productOfferStorageEntity->save();
    }

    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorageEntitiesByProductSkus(array $productSkus): void
    {
        $productConcreteProductOffersStorageEntities = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku_In($productSkus)
            ->find();

        if (empty($productConcreteProductOffersStorageEntities)) {
            return;
        }

        foreach ($productConcreteProductOffersStorageEntities as $productConcreteProductOffersStorageEntity) {
            $productConcreteProductOffersStorageEntity->delete();
        }
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function deleteProductOfferStorageEntitiesByProductOfferReferences(array $productOfferReferences): void
    {
        $productOfferStorageEntities = $this->getFactory()
            ->createProductOfferStoragePropelQuery()
            ->filterByProductOfferReference_In($productOfferReferences)
            ->find();

        if (empty($productOfferStorageEntities)) {
            return;
        }

        foreach ($productOfferStorageEntities as $productOfferStorageEntity) {
            $productOfferStorageEntity->delete();
        }
    }
}
