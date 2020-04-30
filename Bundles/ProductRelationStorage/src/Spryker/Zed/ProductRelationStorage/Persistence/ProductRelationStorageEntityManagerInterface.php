<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStoragePersistenceFactory getFactory()
 */
interface ProductRelationStorageEntityManagerInterface
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
    ): void;

    /**
     * @param int $productAbstractId
     * @param string[] $stores
     *
     * @return void
     */
    public function deleteProductAbstractRelationStorageEntitiesByProductAbstractIdAndStores(
        int $productAbstractId,
        array $stores
    ): void;

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractStorageEntitiesByProductAbstractIds(
        array $productAbstractIds
    ): void;
}
