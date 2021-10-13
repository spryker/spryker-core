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
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductPackagingUnitStorageEntityTransfer>
     */
    public function findProductPackagingUnitStorageEntitiesByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer>
     */
    public function findPackagingProductsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer>
     */
    public function findFilteredProductPackagingUnit(FilterTransfer $filterTransfer, array $productConcreteIds = []): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductPackagingUnitStorageEntityTransfer>
     */
    public function findFilteredProductPackagingUnitStorageEntityTransfers(FilterTransfer $filterTransfer, array $productConcreteIds = []): array;
}
