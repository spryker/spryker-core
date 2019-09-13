<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductPackagingUnitCartOperation implements ProductPackagingUnitCartOperationInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItemToQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $currentItemTransfer) {
            if ($this->getItemIdentifier($currentItemTransfer) === $this->getItemIdentifier($itemTransfer)) {
                $currentItemTransfer->setQuantity(
                    $currentItemTransfer->getQuantity() + $itemTransfer->getQuantity()
                );

                $currentItemTransfer->setAmount(
                    $currentItemTransfer->getAmount()->add($itemTransfer->getAmount())
                );

                return $quoteTransfer;
            }
        }

        $quoteTransfer->getItems()->append($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItemFromQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemIndex => $currentItemTransfer) {
            if ($this->getItemIdentifier($currentItemTransfer) === $this->getItemIdentifier($itemTransfer)) {
                $newQuantity = $currentItemTransfer->getQuantity() - $itemTransfer->getQuantity();
                $newAmount = $currentItemTransfer->getAmount()->subtract($itemTransfer->getAmount());

                if ($newQuantity < 1 || $newAmount->lessThan(1)) {
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

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getItemIdentifier(ItemTransfer $itemTransfer): string
    {
        return $itemTransfer->getGroupKey() ?: $itemTransfer->getSku();
    }
}
