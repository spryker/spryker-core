<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader;

interface ProductReaderInterface
{
    /**
     * @param array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>> $merchantCommissionCalculationRequestItemTransfersGroupedBySku
     *
     * @return array<string, \Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersIndexedBySku(
        array $merchantCommissionCalculationRequestItemTransfersGroupedBySku
    ): array;
}
