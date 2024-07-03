<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader;

interface CategoryReaderInterface
{
    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfersGroupedBySku
     *
     * @return array<int, list<string>>
     */
    public function getCategoryKeysGroupedByIdCategoryNode(array $productCategoryTransfersGroupedBySku): array;
}
