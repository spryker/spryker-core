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
     * @param array<int> $relationIds
     *
     * @return array
     */
    public function getProductRelationsWithProductAbstractByIdRelationIn(array $relationIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function findProductRelationStorageDataTransferByIds(FilterTransfer $filterTransfer, array $ids): array;

    /**
     * @param int $idProductAbstract
     *
     * @return array<string>
     */
    public function getStoresByIdProductAbstractFromStorage(int $idProductAbstract): array;
}
