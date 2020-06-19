<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Operation;

use Generated\Shared\Transfer\QuoteTransfer;

class ItemQuantityCounter implements ItemQuantityCounterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getItemsQuantity(QuoteTransfer $quoteTransfer): int
    {
        $quantity = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }
}
