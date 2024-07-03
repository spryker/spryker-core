<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader;

interface ProductCategoryReaderInterface
{
    /**
     * @param array<string, \Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfersIndexedBySku
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    public function getProductCategoriesGroupedByProductConcreteSku(array $productConcreteTransfersIndexedBySku): array;
}
