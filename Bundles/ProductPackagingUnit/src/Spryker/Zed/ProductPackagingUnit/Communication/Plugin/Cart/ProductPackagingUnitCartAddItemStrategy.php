<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyInterface;

class ProductPackagingUnitCartAddItemStrategy extends ProductPackagingUnitAbstractCartItemOperationStrategy implements CartOperationStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function excute(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $currentItemTransfer) {
            if ($this->getItemIdentifier($currentItemTransfer) === $this->getItemIdentifier($itemTransfer)) {
                $currentItemTransfer->setQuantity(
                    $currentItemTransfer->getQuantity() + $itemTransfer->getQuantity()
                );

                $currentItemTransfer->setAmount(
                    $currentItemTransfer->getAmount() + $itemTransfer->getAmount()
                );

                return $quoteTransfer;
            }
        }

        $quoteTransfer->getItems()->append($itemTransfer);

        return $quoteTransfer;
    }
}
