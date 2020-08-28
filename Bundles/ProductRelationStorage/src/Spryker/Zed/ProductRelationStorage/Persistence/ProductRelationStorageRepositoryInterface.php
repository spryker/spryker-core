<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductRelationStorageRepositoryInterface
{
    /**
     * @param int[] $relationIds
     *
     * @return array
     */
    public function getProductRelationsWithProductAbstractByIdRelationIn(array $relationIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductRelationStorageDataTransferByIds(FilterTransfer $filterTransfer, array $ids): array;

    /**
     * @param int $idProductAbstract
     *
     * @return string[]
     */
    public function getStoresByIdProductAbstractFromStorage(int $idProductAbstract): array;
}
