<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductSalesOrderAmendment\PriceResolver;

use Generated\Shared\Transfer\QuoteTransfer;

interface OriginalSalesOrderItemPriceResolverInterface
{
    /**
     * @param int $salesOrderItemUnitPrice
     * @param int $originalSalesOrderItemUnitPrice
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return int
     */
    public function resolveOriginalSalesOrderItemPrice(
        int $salesOrderItemUnitPrice,
        int $originalSalesOrderItemUnitPrice,
        ?QuoteTransfer $quoteTransfer = null
    ): int;
}
