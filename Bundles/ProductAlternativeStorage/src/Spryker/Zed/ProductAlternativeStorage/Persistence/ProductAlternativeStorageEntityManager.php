<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage;
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStoragePersistenceFactory getFactory()
 */
class ProductAlternativeStorageEntityManager extends AbstractEntityManager implements ProductAlternativeStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage $productAlternativeStorageEntity
     *
     * @return void
     */
    public function saveProductAlternativeStorageEntity(
        SpyProductAlternativeStorage $productAlternativeStorageEntity
    ): void {
        $productAlternativeStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage $productAlternativeStorageEntity
     *
     * @return void
     */
    public function deleteProductAlternativeStorageEntity(
        SpyProductAlternativeStorage $productAlternativeStorageEntity
    ): void {
        $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->filterByIdProductAlternativeStorage($productAlternativeStorageEntity->getIdProductAlternativeStorage())
            ->findOne()
            ->delete();
    }

    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage $productReplacementStorageEntity
     *
     * @return void
     */
    public function saveProductReplacementForStorage(
        SpyProductReplacementForStorage $productReplacementStorageEntity
    ): void {
        $productReplacementStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage $productReplacementStorageEntity
     *
     * @return void
     */
    public function deleteProductReplacementForStorage(
        SpyProductReplacementForStorage $productReplacementStorageEntity
    ): void {
        $this->getFactory()
            ->createProductReplacementForStoragePropelQuery()
            ->filterByIdProductReplacementForStorage($productReplacementStorageEntity->getIdProductReplacementForStorage())
            ->findOne()
            ->delete();
    }
}
