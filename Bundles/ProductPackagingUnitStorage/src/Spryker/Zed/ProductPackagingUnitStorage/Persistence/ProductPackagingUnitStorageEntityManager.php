<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()
 */
class ProductPackagingUnitStorageEntityManager extends AbstractEntityManager implements ProductPackagingUnitStorageEntityManagerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage
     */
    public function saveProductAbstractPackagingStorageEntity(ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer): SpyProductAbstractPackagingStorage
    {
        $productAbstractPackagingStorageEntity = $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->filterByFkProductAbstract($productAbstractPackagingStorageTransfer->getIdProductAbstract())
            ->findOneOrCreate();

        $productAbstractPackagingStorageEntity->setData($productAbstractPackagingStorageTransfer->toArray());

        $productAbstractPackagingStorageEntity->save();

        return $productAbstractPackagingStorageEntity;
    }

    /**
     * @api
     *
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
