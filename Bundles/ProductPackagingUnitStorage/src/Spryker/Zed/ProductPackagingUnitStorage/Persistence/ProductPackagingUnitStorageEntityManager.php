<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()
 */
class ProductPackagingUnitStorageEntityManager extends AbstractEntityManager implements ProductPackagingUnitStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer
     *
     * @return void
     */
    public function saveProductPackagingUnitStorage(ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer): void
    {
        $productPackagingUnitStorageEntity = $this->getFactory()
            ->createProductPackagingUnitStorageQuery()
            ->filterByFkProduct($productPackagingUnitStorageTransfer->getIdProduct())
            ->findOneOrCreate();

        $productPackagingUnitStorageEntity->setData($productPackagingUnitStorageTransfer->toArray());

        $productPackagingUnitStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitStorageEntityTransfer $productPackagingUnitStorageEntity
     *
     * @return void
     */
    public function deleteProductPackagingUnitStorage(SpyProductPackagingUnitStorageEntityTransfer $productPackagingUnitStorageEntity): void
    {
        $productPackagingUnitStorageEntity = $this->getFactory()
            ->createProductPackagingUnitStorageQuery()
            ->filterByFkProduct($productPackagingUnitStorageEntity->getFkProduct());

        $productPackagingUnitStorageEntity->delete();
    }
}
