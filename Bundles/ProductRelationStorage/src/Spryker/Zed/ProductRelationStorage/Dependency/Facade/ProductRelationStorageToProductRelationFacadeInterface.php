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
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function getProductRelationsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int[] $productRelationIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductRelationIds(
        array $productRelationIds
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function findProductRelationsForFilter(FilterTransfer $filterTransfer): array;
}
