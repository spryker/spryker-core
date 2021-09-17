<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductDiscontinuedStorageRepositoryInterface
{
    /**
     * @param array<int> $productDiscontinuedIds
     *
     * @return array<\Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage>
     */
    public function findProductDiscontinuedStorageEntitiesByIds(array $productDiscontinuedIds): array;

    /**
     * @return array<\Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage>
     */
    public function findAllProductDiscontinuedStorageEntities(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productDiscontinuedStorageEntityIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer>
     */
    public function findFilteredProductDiscontinuedStorageEntities(FilterTransfer $filterTransfer, array $productDiscontinuedStorageEntityIds = []): array;
}
