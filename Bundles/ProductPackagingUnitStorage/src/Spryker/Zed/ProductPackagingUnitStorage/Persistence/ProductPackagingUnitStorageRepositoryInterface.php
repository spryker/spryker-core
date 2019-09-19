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
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcretePackagingStorageEntityTransfer[]
     */
    public function findProductConcretePackagingStorageEntitiesByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer[]
     */
    public function findPackagingProductsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer[]
     */
    public function findFilteredProductConcretePackagingUnit(FilterTransfer $filterTransfer, array $productConcreteIds = []): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcretePackagingStorageEntityTransfer[]
     */
    public function findFilteredProductConcretePackagingUnitStorages(FilterTransfer $filterTransfer, array $productConcreteIds = []): array;
}
