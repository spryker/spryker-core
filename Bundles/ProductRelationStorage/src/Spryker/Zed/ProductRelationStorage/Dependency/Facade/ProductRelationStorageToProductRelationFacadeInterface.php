<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Dependency\Facade;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductRelationStorageToProductRelationFacadeInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductRelationTransfer>
     */
    public function getProductRelationsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productRelationIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductRelationIds(
        array $productRelationIds
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductRelationTransfer>
     */
    public function findProductRelationsForFilter(FilterTransfer $filterTransfer): array;
}
