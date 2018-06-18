<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
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
            ->createProductAlternativeStorageQuery()
            ->filterByIdProductAlternativeStorage($productAlternativeStorageEntityTransfer->getIdProductAlternativeStorage())
            ->delete();
    }
}
