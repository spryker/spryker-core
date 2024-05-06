<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStoragePersistenceFactory getFactory()
 */
class ProductOfferShipmentTypeStorageEntityManager extends AbstractEntityManager implements ProductOfferShipmentTypeStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer $productOfferShipmentTypeStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveProductOfferShipmentTypeStorage(
        ProductOfferShipmentTypeStorageTransfer $productOfferShipmentTypeStorageTransfer,
        string $storeName
    ): void {
        $productOfferShipmentTypeStorageEntity = $this->getFactory()
            ->createProductOfferShipmentTypeStorageQuery()
            ->filterByProductOfferReference($productOfferShipmentTypeStorageTransfer->getProductOfferReferenceOrFail())
            ->filterByStore($storeName)
            ->findOneOrCreate();
        $productOfferShipmentTypeStorageEntity->setData($productOfferShipmentTypeStorageTransfer->toArray());

        $productOfferShipmentTypeStorageEntity->save();
    }

    /**
     * @param list<string> $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypeStorages(array $productOfferReferences, ?string $storeName = null): void
    {
        $productOfferShipmentTypeStorageQuery = $this->getFactory()
            ->createProductOfferShipmentTypeStorageQuery()
            ->filterByProductOfferReference_In($productOfferReferences);
        if ($storeName) {
            $productOfferShipmentTypeStorageQuery->filterByStore($storeName);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $productOfferShipmentTypeStorageCollection */
        $productOfferShipmentTypeStorageCollection = $productOfferShipmentTypeStorageQuery->find();
        $productOfferShipmentTypeStorageCollection->delete();
    }
}
