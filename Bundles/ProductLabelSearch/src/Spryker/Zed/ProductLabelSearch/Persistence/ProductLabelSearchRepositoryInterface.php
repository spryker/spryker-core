<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Persistence;

interface ProductLabelSearchRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductLabelEntityTransfer>
     */
    public function getProductLabelsByIdProductAbstractIn(array $productAbstractIds): array;

    /**
     * @param array<int> $productLabelIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductLabelIds(array $productLabelIds): array;
}
