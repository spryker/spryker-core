<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStoragePersistenceFactory getFactory()
 */
class ProductOfferServicePointStorageEntityManager extends AbstractEntityManager implements ProductOfferServicePointStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveProductOfferServiceForStore(
        ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer,
        string $storeName
    ): void {
        $productOfferServiceStorageEntity = $this->getFactory()
            ->getProductOfferServiceStorageQuery()
            ->filterByProductOfferReference($productOfferServiceStorageTransfer->getProductOfferReferenceOrFail())
            ->filterByStore($storeName)
            ->findOneOrCreate();

        $servicePointStorageEntity = $this->getFactory()
            ->createProductOfferServiceStorageMapper()
            ->mapProductOfferServiceStorageTransferToProductOfferServiceStorageEntity($productOfferServiceStorageTransfer, $productOfferServiceStorageEntity);

        $servicePointStorageEntity->save();
    }

    /**
     * @param list<string> $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferServiceStorageByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void
    {
        $productOfferServiceStorageQuery = $this->getFactory()
            ->getProductOfferServiceStorageQuery()
            ->filterByProductOfferReference_In($productOfferReferences);

        if ($storeName) {
            $productOfferServiceStorageQuery->filterByStore($storeName);
        }

        $productOfferServiceStorageQuery->find()->delete();
    }
}
