<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Persistence;

use Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStoragePersistenceFactory getFactory()
 */
class ProductDiscontinuedStorageEntityManager extends AbstractEntityManager implements ProductDiscontinuedStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductDiscontinuedStorageEntity(
        SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer
    ): void {
        $this->save($productDiscontinuedStorageEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer
     *
     * @return void
     */
    public function deleteProductDiscontinuedStorageEntity(
        SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer
    ): void {
        $this->getFactory()
            ->createProductDiscontinuedStorageQuery()
            ->filterByIdProductDiscontinuedStorage($productDiscontinuedStorageEntityTransfer->getIdProductDiscontinuedStorage())
            ->delete();
    }
}
