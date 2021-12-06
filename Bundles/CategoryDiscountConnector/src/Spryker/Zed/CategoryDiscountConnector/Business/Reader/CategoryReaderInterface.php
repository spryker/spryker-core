<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business\Reader;

interface CategoryReaderInterface
{
    /**
     * @return array<string, string>
     */
    public function getCategoryNamesIndexedByCategoryKey(): array;

    /**
     * @param array<int, array<\Generated\Shared\Transfer\ProductCategoryTransfer>> $groupedProductCategoryTransfers
     *
     * @return array<int, array<string>>
     */
    public function getCategoryKeysGroupedByIdCategoryNode(array $groupedProductCategoryTransfers): array;
}
