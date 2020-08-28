<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

interface ProductOptionRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer[]
     */
    public function getProductAbstractOptionGroupStatusesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getOrderItemsWithProductOptions(array $salesOrderItemIds): array;

    /**
     * @param string[] $productOptionSkus
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueTransfer[]
     */
    public function getProductOptionValuesBySkus(array $productOptionSkus): array;
}
