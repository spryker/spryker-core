<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()
 */
class ProductPackagingUnitStorageEntityManager extends AbstractEntityManager implements ProductPackagingUnitStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer
     *
     * @return void
     */
    public function saveProductAbstractPackagingStorageEntity(ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer): void
    {
        $productAbstractPackagingStorageEntity = $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->filterByFkProductAbstract($productAbstractPackagingStorageTransfer->getIdProductAbstract())
            ->findOneOrCreate();

        $productAbstractPackagingStorageEntity->setData($productAbstractPackagingStorageTransfer->toArray());

        $productAbstractPackagingStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer $productAbstractPackagingStorageEntity
     *
     * @return void
     */
    public function deleteProductAbstractPackagingStorageEntity(SpyProductAbstractPackagingStorageEntityTransfer $productAbstractPackagingStorageEntity): void
    {
        $productAbstractPackagingStorageEntity = $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->filterByFkProductAbstract($productAbstractPackagingStorageEntity->getFkProductAbstract());

        $productAbstractPackagingStorageEntity->delete();
    }
}
