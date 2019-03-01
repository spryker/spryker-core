<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductPackagingUnitStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage[]
     */
    public function findProductAbstractPackagingStorageEntitiesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function findPackagingProductsByProductAbstractId(int $idProductAbstract): array;

    /**
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage[]
     */
    public function findAllProductAbstractPackagingStorageEntities(): array;

    /**
     * @return int[]
     */
    public function findProductAbstractIdsWithProductPackagingUnit(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findFilteredProductAbstractPackagingUnitStorages(FilterTransfer $filterTransfer, array $productAbstractIds = []): array;
}
