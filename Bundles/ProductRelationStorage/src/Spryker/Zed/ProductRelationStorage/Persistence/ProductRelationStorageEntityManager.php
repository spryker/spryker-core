<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStoragePersistenceFactory getFactory()
 */
class ProductRelationStorageEntityManager extends AbstractEntityManager implements ProductRelationStorageEntityManagerInterface
{
    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer $productAbstractRelationStorageTransfer
     *
     * @return void
     */
    public function saveProductAbstractRelationStorageEntity(
        int $idProductAbstract,
        ProductAbstractRelationStorageTransfer $productAbstractRelationStorageTransfer
    ): void {
        $productAbstractRelationStorageEntity = $this->getFactory()
            ->createSpyProductAbstractRelationStorageQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByStore($productAbstractRelationStorageTransfer->getStore())
            ->findOneOrCreate();

        $productAbstractRelationStorageEntity->setData($productAbstractRelationStorageTransfer->toArray())
            ->save();

        $productAbstractRelationStorageEntity
            ->setIsSendingToQueue($productAbstractRelationStorageEntity->isSynchronizationEnabled())
            ->syncPublishedMessage();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractRelationStorageEntitiesByProductAbstractIds(
        array $productAbstractIds
    ): void {
        $this->getFactory()
            ->createSpyProductAbstractRelationStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->delete();
    }
}
