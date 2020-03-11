<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorage;
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
            ->findOne();

        if (!$productAbstractRelationStorageEntity) {
            $this->createProductAbstractRelationStorageEntity($idProductAbstract, $productAbstractRelationStorageTransfer);

            return;
        }

        $this->updateProductAbstractRelationStorageEntity($productAbstractRelationStorageEntity, $productAbstractRelationStorageTransfer);
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

    /**
     * @param \Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorage $productAbstractRelationStorageEntity
     * @param \Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer $productAbstractRelationStorageTransfer
     *
     * @return void
     */
    protected function updateProductAbstractRelationStorageEntity(
        SpyProductAbstractRelationStorage $productAbstractRelationStorageEntity,
        ProductAbstractRelationStorageTransfer $productAbstractRelationStorageTransfer
    ): void {
        $productAbstractRelationStorageEntity->setData($productAbstractRelationStorageTransfer->toArray());
        $productAbstractRelationStorageEntity->setStore($productAbstractRelationStorageTransfer->getStore());
        $productAbstractRelationStorageEntity->setIsSendingToQueue(true);
        $productAbstractRelationStorageEntity->save();
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer $productAbstractRelationStorageTransfer
     *
     * @return void
     */
    protected function createProductAbstractRelationStorageEntity(
        int $idProductAbstract,
        ProductAbstractRelationStorageTransfer $productAbstractRelationStorageTransfer
    ): void {
        $productAbstractRelationStorageEntity = new SpyProductAbstractRelationStorage();
        $productAbstractRelationStorageEntity->setStore($productAbstractRelationStorageTransfer->getStore());
        $productAbstractRelationStorageEntity->setFkProductAbstract($idProductAbstract);
        $productAbstractRelationStorageEntity->setData($productAbstractRelationStorageTransfer->toArray());

        $productAbstractRelationStorageEntity->save();
    }
}
