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
     * @param array<int, list<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfersGroupedByIdProductAbstract
     *
     * @return array<int, list<string>>
     */
    public function getCategoryKeysGroupedByIdCategoryNode(array $productCategoryTransfersGroupedByIdProductAbstract): array;
}
