<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStoragePersistenceFactory getFactory()
 */
class ProductAlternativeStorageEntityManager extends AbstractEntityManager implements ProductAlternativeStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductAlternativeStorageEntity(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
    ): void {
        $this->save($productAlternativeStorageEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
     *
     * @return void
     */
    public function deleteProductAlternativeStorageEntity(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
    ): void {
        $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->filterByIdProductAlternativeStorage($productAlternativeStorageEntityTransfer->getIdProductAlternativeStorage())
            ->findOne()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer $productReplacementStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductReplacementStorage(
        SpyProductReplacementStorageEntityTransfer $productReplacementStorageEntityTransfer
    ): void {
        $this->save($productReplacementStorageEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer $productReplacementStorageEntityTransfer
     *
     * @return void
     */
    public function deleteProductReplacementStorage(
        SpyProductReplacementStorageEntityTransfer $productReplacementStorageEntityTransfer
    ): void {
        $this->getFactory()
            ->createProductReplacementStoragePropelQuery()
            ->filterByIdProductReplacementStorage($productReplacementStorageEntityTransfer->getIdProductReplacementStorage())
            ->findOne()
            ->delete();
    }
}
