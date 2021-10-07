<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductQuantityStorageRepositoryInterface
{
    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer>
     */
    public function findProductQuantityStorageEntitiesByProductIds(array $productIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer>
     */
    public function findAllProductQuantityStorageEntities(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer>
     */
    public function findFilteredProductQuantityStorageEntities(FilterTransfer $filterTransfer, array $productIds = []): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductQuantityEntityTransfer>
     */
    public function getProductQuantityEntityTransfersByProductIds(array $productIds): array;
}
