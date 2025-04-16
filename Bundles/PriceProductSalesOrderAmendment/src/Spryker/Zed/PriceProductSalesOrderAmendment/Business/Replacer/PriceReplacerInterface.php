<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PriceReplacerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $originalSalesOrderItemUnitPrice
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function replaceOriginalSalesOrderItemUnitPrice(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer,
        int $originalSalesOrderItemUnitPrice
    ): ItemTransfer;
}
