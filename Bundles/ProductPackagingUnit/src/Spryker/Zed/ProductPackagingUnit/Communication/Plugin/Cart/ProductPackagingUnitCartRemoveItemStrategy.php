<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyInterface;

class ProductPackagingUnitCartRemoveItemStrategy extends ProductPackagingUnitAbstractCartItemOperationStrategy implements CartOperationStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function excute(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemIndex => $currentItemTransfer) {
            if ($this->getItemIdentifier($currentItemTransfer) === $this->getItemIdentifier($itemTransfer)) {
                $newQuantity = $currentItemTransfer->getQuantity() - $itemTransfer->getQuantity();
                $newAmount = $currentItemTransfer->getAmount() - $itemTransfer->getAmount();

                if ($newQuantity < 1 || $newAmount < 1) {
                    $quoteTransfer->getItems()->offsetUnset($itemIndex);
                    break;
                }

                $currentItemTransfer->setQuantity($newQuantity);
                $currentItemTransfer->setAmount($newAmount);
                break;
            }
        }

        return $quoteTransfer;
    }
}
