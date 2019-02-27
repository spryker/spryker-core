<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

use Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStoragePersistenceFactory getFactory()
 */
class ProductQuantityStorageEntityManager extends AbstractEntityManager implements ProductQuantityStorageEntityManagerInterface
{
    /**
     * @param int $idProductQuantityStorage
     * @param bool $isSendingToQueue
     *
     * @return void
     */
    public function deleteProductQuantityStorage(int $idProductQuantityStorage, bool $isSendingToQueue = true): void
    {
        $spyProductQuantityStorageEntity = $this->getFactory()
            ->createProductQuantityStorageQuery()
            ->filterByIdProductQuantityStorage($idProductQuantityStorage)
            ->findOne();

        $spyProductQuantityStorageEntity->setIsSendingToQueue($isSendingToQueue);
        $spyProductQuantityStorageEntity->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer $productQuantityStorageEntity
     * @param bool $isSendingToQueue
     *
     * @return void
     */
    public function saveProductQuantityStorageEntity(
        SpyProductQuantityStorageEntityTransfer $productQuantityStorageEntity,
        bool $isSendingToQueue = true
    ): void {
        $productQuantityStorageEntity->requireFkProduct();

        $spyProductQuantityStorageEntity = $this->getFactory()
            ->createProductQuantityStorageQuery()
            ->filterByFkProduct($productQuantityStorageEntity->getFkProduct())
            ->findOneOrCreate();

        $spyProductQuantityStorageEntity = $this->getFactory()
            ->createProductQuantityStorageMapper()
            ->hydrateSpyProductQuantityStorageEntity(
                $spyProductQuantityStorageEntity,
                $productQuantityStorageEntity
            );
        $spyProductQuantityStorageEntity->setIsSendingToQueue($isSendingToQueue);

        $spyProductQuantityStorageEntity->save();
    }
}
